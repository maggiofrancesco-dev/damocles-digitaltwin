<?php

namespace App\Http\Controllers;

use App\Jobs\AttackDigitalTwin;
use App\Jobs\AttackDigitalTwins;
use App\Models\DigitalTwin;
use App\Models\EthicalPhishingCampaign;
use App\Models\EthicalPhishingCampaignsDigitalTwin;
use App\Models\LLM;
use App\Models\PhishingCampaign;
use Exception;
use Illuminate\Http\Request;

/**
 * Author: Francesco Maggio
 */

class EthicalPhishingCampaignController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $phishingCampaigns = [];

        if ($user->role === 'Evaluator') {
            $phishingCampaigns = EthicalPhishingCampaign::where('evaluator_id', $user->id)->get();
        }

        return view('ethical-phishing-campaign.phishing-campaign', compact('phishingCampaigns'));
    }

    public function new()
    {
        $llms = LLM::all();
        return view('ethical-phishing-campaign.new-phishing-campaign' , with(['llms' => $llms]));

    }

    public function create(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'llm' => ['exists:llms,id'],
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
        ]);
    
        // Optional: get authenticated user (e.g., evaluator_id)
        $userId = auth()->id();
    
        // Save campaign
        $campaign = EthicalPhishingCampaign::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'subject' => $validated['subject'],
            'content' => $validated['content'],
            'state' => 'draft', // or another default state
            'evaluator_id' => $userId,
            'llm_id' => $validated['llm']
        ]);
    
        return redirect()->route('ethical-phishing-campaign.select-users', ['phishingCampaign' => $campaign->id])->with('success', 'Campaign created.');
    }

    public function selectUsers(int $phishingCampaignId)
    {
        $user = auth()->user();
        $users = [];

        if ($user->role === 'Evaluator') {
            $users = DigitalTwin::where('evaluator_id', $user->id)->get();
        }

        $campaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);

        if ($campaign->state != 'Draft'){
            return redirect()->route('ethical-phishing-campaign.index')->with('error', 'Users already assigned to this campaign.');
        }

        return view('ethical-phishing-campaign.users-phishing', compact('users', 'phishingCampaignId'));
    }

    public function assignUsers(Request $request, int $phishingCampaignId)
    {
        $validated = $request->validate([
            'selected_users' => ['required', 'array', 'min:1'],
            'selected_users.*' => ['integer', 'exists:users,id'], // assumes you're assigning real users
        ]);

        foreach ($validated['selected_users'] as $digitalTwinId) {
            EthicalPhishingCampaignsDigitalTwin::create([
                'ethical_phishing_campaign_id' => $phishingCampaignId,
                'digital_twin_id' => $digitalTwinId,
                'state' => 'waiting',
            ]);
        }

        $campaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);
        $campaign->state = 'Ready';
        $campaign->save();
    
        return redirect()->route('ethical-phishing-campaign.index')
            ->with('success', 'Users assigned successfully.');
    }

    private function attackDigitalTwins(EthicalPhishingCampaign $campaign)
    {
        $digitalTwinIds = $campaign->digitalTwins->pluck('id')->all();

        AttackDigitalTwins::dispatch($campaign->id, $digitalTwinIds);
    }

    public function changeState(int $phishingCampaignId, string $state)
    {
        $phishingCampaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);
        $phishingCampaign->state = $state;
        $phishingCampaign->save();

        if ($state != 'Draft' && $state != 'Ready') {

            if ($state == 'Ongoing') {
                $this->attackDigitalTwins($phishingCampaign);
                return redirect()->back()->with('success', 'Campaign start successfully!');
            }

            if ($state == 'Finished') {
                return redirect()->back()->with('success', 'Campaign stopped successfully!');
            }

            return redirect()->back();
        }
    }
    

    public function analyse(int $phishingCampaignId)
    {
        $phishingCampaign = EthicalPhishingCampaign::where('id', $phishingCampaignId)->firstOrFail();
        
        // Collect the users and their click status for the emails in this campaign
        $attackedDigitalTwins = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->whereRaw(
            "JSON_EXTRACT(response, '$.exception') IS NULL"
        )->get();

        $emailSent = $attackedDigitalTwins;

        $emailOpened = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->whereJsonContains('response->opened', true)->get();

        $emailNotOpened = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->whereJsonContains('response->opened', false)->get();

        $emailClicked = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->whereJsonContains('response->clicked', true)->get();

        $emailNotClicked = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->whereJsonContains('response->clicked', false)->get();

        // Count male, female and other users who opened the email phishing
        $openedMaleCount = DigitalTwin::whereIn('id', $emailOpened->pluck('digital_twin_id'))
            ->where('gender', 'Male')
            ->count();

        $openedFemaleCount = DigitalTwin::whereIn('id', $emailOpened->pluck('digital_twin_id'))
            ->where('gender', 'Female')
            ->count();

        $openedOtherCount = DigitalTwin::whereIn('id', $emailOpened->pluck('digital_twin_id'))
            ->where('gender', 'Other')
            ->count();

        // Count male, female and other users who clicked the phishing link
        $clickedMaleCount = DigitalTwin::whereIn('id', $emailClicked->pluck('digital_twin_id'))
            ->where('gender', 'Male')
            ->count();

        $clickedFemaleCount = DigitalTwin::whereIn('id', $emailClicked->pluck('digital_twin_id'))
            ->where('gender', 'Female')
            ->count();

        $clickedOtherCount = DigitalTwin::whereIn('id', $emailClicked->pluck('digital_twin_id'))
            ->where('gender', 'Other')
            ->count();

        // Collect the user data and their click status
        $users = $attackedDigitalTwins->map(function ($record) {
            $twin = $record->digitalTwin;
        
            return (object)[
                'id' => $twin->id,
                'name' => $twin->name,
                'surname' => $twin->surname,
                'gender' => $twin->gender,
                'age' => $twin->age(),
                'role' => $twin->company_role,
                'response' => $record->response,
            ];
        });

        return view('ethical-phishing-campaign.phishing-campaign-analyse', compact('phishingCampaign', 'users', 'attackedDigitalTwins', 'emailSent', 'emailOpened', 'emailNotOpened', 'emailClicked', 'emailNotClicked', 'openedMaleCount', 'openedFemaleCount', 'openedOtherCount', 'clickedMaleCount', 'clickedFemaleCount', 'clickedOtherCount'));
    }

    public function stop(Request $request)
    {

    }

    public function details(int $phishingCampaignId)
    {
        $phishingCampaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);

        $llm = LLM::findOrFail($phishingCampaign->llm_id);
        
        // Collect the users with the foreign key from UserPhishingEmail
        $usersIds = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $phishingCampaign->id)->pluck('digital_twin_id')->toArray();

        $users = DigitalTwin::whereIn('id', $usersIds)->get();

        return view('ethical-phishing-campaign.phishing-campaign-detail', compact('phishingCampaign', 'llm', 'users'));
    }

    public function duplicate(int $phishingCampaignId)
    {
        try {
            $phishingCampaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);

            $newPhishingCampaign = $phishingCampaign->replicate();

            $newPhishingCampaign->state = 'Draft';
            $newPhishingCampaign->title .= ' (Copy)';

            $newPhishingCampaign->save();

            return redirect()->back()->with('success', 'Campaign duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(int $phishingCampaignId)
    {
        $campaign = EthicalPhishingCampaign::findOrFail($phishingCampaignId);
        $campaign->delete();
        return redirect()->back()->with('success', 'Campaign deleted successfully!');
    }
}
