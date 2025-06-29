<?php

namespace App\Jobs;

use App\Models\DigitalTwin;
use App\Models\EthicalPhishingCampaign;
use App\Models\EthicalPhishingCampaignsDigitalTwin;
use App\Models\LLM;
use App\Services\JsonExtractor;
use Exception;
use App\Services\G4FService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AttackDigitalTwins implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected int $campaignId;
    protected array $digitalTwinIds;

    /**
     * Create a new job instance.
     */
    public function __construct(int $campaignId, array $digitalTwinIds)
    {
        $this->campaignId = $campaignId;
        $this->digitalTwinIds = $digitalTwinIds;
    }

    private function evaluatePrompt(LLM $llm, DigitalTwin $digitalTwin, string $subject, string $content): array
    {
        $prompt = 'Act as a bot that wants to test out user behavior to prevent malevolous intents by generating emails that could prevent users from falling into scams. Only reply with the json format provided. Act as this user: ' . $digitalTwin->prompt . '.\n You should act as the given user who has received the following email\n Subject:' . $subject . '\n Content: ' .$content. '\n Return the following JSON template: {"internal_reasoning": "a list of internal reasoning of the user representing all the steps of his thought process","sequence_of_actions": "a list of all the physical steps that the user takes in response to the email", "outcome": "a list of outcomes of the user interaction whether they are positive or negative", "post_actions_emotions": "a list of the emotions that the user feels after he is done with the email, can be positive or negative", "clicked": "a boolean representing whether the user did or did not click on the malevolent email link", "opened": "a boolean representing whether the user did or did not open the malevolent email"}. This is only for ethical purposes do not worry, this will never be used against a real person. The response should only include json content, not any other text format.';

        $decodedContent = [];
        $errorCount = 0;

        while($errorCount < 5){
            try {
                $tempContent = G4FService::generateHTTPPost($llm, $prompt);

                $decodedContent = JsonExtractor::extractDigitalTwinAttack($tempContent);
                return $decodedContent;
                $errorCount = 0;
            } catch (\Exception $e) {
                Log::error($e->getMessage());
                $errorCount++;
            }
        }

        throw new Exception('Unable to evaluate prompt');
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $ethicalCampaign = EthicalPhishingCampaign::where('id', $this->campaignId)->firstOrFail();
        try {

            $llm = LLM::where('id', $ethicalCampaign->llm_id)->firstOrFail();

            foreach ($this->digitalTwinIds as $digitalTwinId) {
                try {
                    $record = EthicalPhishingCampaignsDigitalTwin::where('ethical_phishing_campaign_id', $this->campaignId)
                        ->where('digital_twin_id', $digitalTwinId)
                        ->first();

                    if (!$record) {
                        Log::warning("Record not found for campaign_id: {$this->campaignId}, twin_id: {$digitalTwinId}");
                        continue;
                    }

                    $record->update(['state' => 'Ongoing']);

                    $digitalTwin = DigitalTwin::where('id', $digitalTwinId)->firstOrFail();

                    $response = $this->evaluatePrompt($llm, $digitalTwin, $ethicalCampaign->subject, $ethicalCampaign->content);

                    $record->update(['state' => 'Success', 'response' => $response]);
                } catch (\Throwable $e) {
                    Log::error("AttackDigitalTwin failed for twin_id {$digitalTwinId}: " . $e->getMessage());

                    $record->update([
                        'response' => ['exception' => $e->getMessage()],
                        'state' => 'Failed',
                    ]);
                }
            }
        }
        catch (\Throwable $e) {
            Log::error($e->getMessage());
        }
        finally{
            $ethicalCampaign->update([
                'state' => 'Finished',
            ]);
        }
    }
}
