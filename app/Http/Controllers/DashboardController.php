<?php

namespace App\Http\Controllers;

use App\Models\EthicalPhishingCampaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use NunoMaduro\Collision\Adapters\Phpunit\State;

use App\Models\User;
use App\Models\LLM;
use App\Models\Questionnaire;
use App\Models\QuestionnaireCampaign;
use App\Models\PhishingCampaign;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $adminsCount = User::where('role', 'Admin')->get()->count();
        $evaluatorsCount = User::where('role', 'Evaluator')->get()->count();
        $usersCount = User::where('role', 'User')->get()->count();

        $llmsCount = LLM::all()->count();
      
        $phishingCampaigns = [];
    
        if ($user->role === 'Admin') {
            $phishingCampaigns = PhishingCampaign::all();
        } elseif ($user->role === 'Evaluator') {
            $phishingCampaigns = PhishingCampaign::where('evaluator_id', $user->id)->get();
        }

        $ethicalPhishingCampaigns = [];

        if ($user->role === 'Admin') {
            $ethicalPhishingCampaigns = EthicalPhishingCampaign::all();
        } elseif ($user->role === 'Evaluator') {
            $ethicalPhishingCampaigns = EthicalPhishingCampaign::where('evaluator_id', $user->id)->get();
        }

        $totalPhishingCampaigns = $phishingCampaigns->count();

        $totalEthicalPhishingCampaigns = $ethicalPhishingCampaigns->count();

        $questionnairesCount = Questionnaire::all()->count();
        $questionnairesCampaigns = [];
    
        if ($user->role === 'Admin') {
            $questionnairesCampaigns = QuestionnaireCampaign::all();
        } elseif ($user->role === 'Evaluator') {
            $questionnairesCampaigns = QuestionnaireCampaign::where('evaluator_id', $user->id)->get();
        }

        $totalQuestionnaireCampaigns = $questionnairesCampaigns->count();

        if ($phishingCampaigns->isNotEmpty()) {
            $phishingCampaignsDraft = $phishingCampaigns->where('state', 'Draft')->count();
            $phishingCampaignsReady = $phishingCampaigns->where('state', 'Ready')->count();
            $phishingCampaignsOngoing = $phishingCampaigns->where('state', 'Ongoing')->count();
            $phishingCampaignsFinished = $phishingCampaigns->where('state', 'Finished')->count();
        } else {
            $phishingCampaignsDraft = 0;
            $phishingCampaignsReady = 0;
            $phishingCampaignsOngoing = 0;
            $phishingCampaignsFinished = 0;
        }

        if ($ethicalPhishingCampaigns->isNotEmpty()) {
            $ethicalPhishingCampaignsDraft = $ethicalPhishingCampaigns->where('state', 'Draft')->count();
            $ethicalPhishingCampaignsReady = $ethicalPhishingCampaigns->where('state', 'Ready')->count();
            $ethicalPhishingCampaignsOngoing = $ethicalPhishingCampaigns->where('state', 'Ongoing')->count();
            $ethicalPhishingCampaignsFinished = $ethicalPhishingCampaigns->where('state', 'Finished')->count();
        } else {
            $ethicalPhishingCampaignsDraft = 0;
            $ethicalPhishingCampaignsReady = 0;
            $ethicalPhishingCampaignsOngoing = 0;
            $ethicalPhishingCampaignsFinished = 0;
        }

        if ($questionnairesCampaigns->isNotEmpty()) {
            $questionnairesCampaignsDraft = $questionnairesCampaigns->where('state', 'Draft')->count();
            $questionnairesCampaignsReady = $questionnairesCampaigns->where('state', 'Ready')->count();
            $questionnairesCampaignsOngoing = $questionnairesCampaigns->where('state', 'Ongoing')->count();
            $questionnairesCampaignsFinished = $questionnairesCampaigns->where('state', 'Finished')->count();
        } else {
            $questionnairesCampaignsDraft = 0;
            $questionnairesCampaignsReady = 0;
            $questionnairesCampaignsOngoing = 0;
            $questionnairesCampaignsFinished = 0;
        }

        return view('dashboard.dashboard', compact(
            'adminsCount',
            'evaluatorsCount',
            'usersCount',
            'llmsCount',
            'totalPhishingCampaigns',
            'phishingCampaignsDraft',
            'phishingCampaignsReady',
            'phishingCampaignsOngoing',
            'phishingCampaignsFinished',
            'totalEthicalPhishingCampaigns',
            'ethicalPhishingCampaignsDraft',
            'ethicalPhishingCampaignsReady',
            'ethicalPhishingCampaignsOngoing',
            'ethicalPhishingCampaignsFinished',
            'questionnairesCount',
            'totalQuestionnaireCampaigns',
            'questionnairesCampaignsDraft',
            'questionnairesCampaignsReady',
            'questionnairesCampaignsOngoing',
            'questionnairesCampaignsFinished'
        ));
    }
}
