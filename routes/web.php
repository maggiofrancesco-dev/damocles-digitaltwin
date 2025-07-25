<?php

use App\Http\Controllers\HAISController;
use App\Http\Controllers\DAMOCLESController;
use App\Http\Controllers\LLMController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DigitalTwinController;
use App\Http\Controllers\EthicalPhishingCampaignController;
use App\Http\Controllers\FakeUserController;
use App\Http\Controllers\QuestionnaireCampaignController;
use App\Http\Controllers\PhishingCampaignController;
use App\Http\Controllers\PhishingContextController;
use App\Http\Controllers\PhishingPersuasionController;
use App\Http\Controllers\PhishingEmotionalTriggerController;
use App\Http\Controllers\HumanFactorQuestionnaireController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'hf_questionnaire_completed', 'verified'])->group(function () {

    // Human Factor Questionnaire routes
    Route::get('human-factor-questionnaire', [HumanFactorQuestionnaireController::class, 'create'])->name('hf.questionnaire');
    Route::post('human-factor-questionnaire', [HumanFactorQuestionnaireController::class, 'store'])->name('hf.questionnaire.store');

    Route::middleware(['role:Admin'])->group(function () {
        Route::get('/llms', [LLMController::class, 'index'])->name('llms.index');
        Route::post('/llm/create', [LLMController::class, 'create'])->name('llm.create');
        Route::put('/llm/{llm}', [LLMController::class, 'update'])->name('llm.update');
        Route::delete('/llm/{llm}', [LLMController::class, 'destroy'])->name('llm.destroy');
        Route::patch('/profile/updateFromAdmin', [UserController::class, 'updateFromAdmin'])->name('profile.updateFromAdmin');
        Route::delete('/profile/{id}', [UserController::class, 'destroyFromAdmin'])->name('profile.destroyFromAdmin');
        Route::get('/users/{role?}', [UserController::class, 'index'])
            ->middleware(['auth', 'verified'])
            ->name('users');
    });

    Route::middleware(['role:Evaluator'])->group(function () {

        // Questionnaires campaigns
        Route::get('/questionnaires', [QuestionnaireCampaignController::class, 'questionnaires'])->name('questionnaires');
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');

        Route::get('/questionnaires-campaign/new', [QuestionnaireCampaignController::class, 'new'])->name('questionnaires-campaign.new');
        Route::post('/questionnaire-campaign/create', [QuestionnaireCampaignController::class, 'create'])->name('questionnaire-campaign.create');

        Route::get('/questionnaire-campaign/questionnaires/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'questionnairesQuestionnaireCampaign'])->name('questionnaire-campaign.questionnaires');
        Route::post('/questionnaire-campaign/questionnaires-questionnaire-campaign', [QuestionnaireCampaignController::class, 'saveQuestionnairesQuestionnaireCampaign'])->name('questionnaire-campaign.questionnaires-questionnaire-campaign');

        Route::get('/questionnaire-campaign/users/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'users'])->name('questionnaire-campaign.users');
        Route::post('/questionnaire-campaign/users-questionnaire', [QuestionnaireCampaignController::class, 'saveUsersQuestionnaireCampaign'])->name('questionnaire-campaign.users-questionnaire-campaign');

        Route::post('/questionnaire-campaign/change-state/{questionnaireCampaign}/{state}', [QuestionnaireCampaignController::class, 'changeState'])->name('questionnaire-campaign.change-state');
        Route::get('/questionnaire-campaign/analyse/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'analyse'])->name('questionnaire-campaign.analyse');
        Route::get('/questionnaire-campaign/stop/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'stop'])->name('questionnaire-campaign.stop');
        Route::get('/questionnaire-campaign/detail/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'details'])->name('questionnaire-campaign.details');
        Route::get('/questionnaire-campaign/duplicate/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'duplicate'])->name('questionnaire-campaign.duplicate');
        Route::delete('/questionnaire-campaign/delete/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'destroy'])->name('questionnaire-campaign.destroy');

        // Questionnaires
        Route::delete('/result/{answer}', [QuestionnaireCampaignController::class, 'deleteAnswer'])->name('result.destroy');

        // HAIS
        Route::get('/hais', [HAISController::class, 'preview'])->name('hais.preview');
        Route::get('/hais/result/{hais}', [HAISController::class, 'result'])->name('hais.result');
        //Route::delete('/hais/result/{hais}', [HAISController::class, 'destroy'])->name('hais.destroy');

        // DAMOCLES
        Route::get('/damocles', [DAMOCLESController::class, 'preview'])->name('damocles.preview');
        Route::get('/damocles/result/{damocles}', [DAMOCLESController::class, 'result'])->name('damocles.result');
        //Route::delete('/damocles/result/{damocles}', [DAMOCLESController::class, 'destroy'])->name('damocles.destroy');

        // Phishing campaigns
        Route::get('/phishing-campaign', [PhishingCampaignController::class, 'index'])->name('phishing-campaign.index');

        Route::get('/phishing-campaign/new', [PhishingCampaignController::class, 'new'])->name('phishing-campaign.new');
        Route::post('/phishing-campaign/create', [PhishingCampaignController::class, 'create'])->name('phishing-campaign.create');

        Route::get('/phishing-campaign/emails/{phishingCampaign}', [PhishingCampaignController::class, 'generate'])->name('phishing-campaign.generate-emails');
        Route::post('/phishing-campaign/emails', [PhishingCampaignController::class, 'savePhishingEmails'])->name('phishing-campaign.save-emails');

        Route::get('/phishing-campaign/users/{phishingCampaign}', [PhishingCampaignController::class, 'users'])->name('phishing-campaign.users');
        Route::post('/phishing-campaign/users-phishing-email', [PhishingCampaignController::class, 'saveUsersPhishingEmails'])->name('phishing-campaign.users-phishing-email');

        Route::post('/phishing-campaign/change-state/{phishingCampaign}/{state}', [PhishingCampaignController::class, 'changeState'])->name('phishing-campaign.change-state');
        Route::get('/phishing-campaign/analyse/{phishingCampaign}', [PhishingCampaignController::class, 'analyse'])->name('phishing-campaign.analyse');
        Route::get('/phishing-campaign/stop/{phishingCampaign}', [PhishingCampaignController::class, 'stop'])->name('phishing-campaign.stop');
        Route::get('/phishing-campaign/detail/{phishingCampaign}', [PhishingCampaignController::class, 'details'])->name('phishing-campaign.details');
        Route::get('/phishing-campaign/duplicate/{phishingCampaign}', [PhishingCampaignController::class, 'duplicate'])->name('phishing-campaign.duplicate');
        Route::delete('/phishing-campaign/delete/{phishingCampaign}', [PhishingCampaignController::class, 'destroy'])->name('phishing-campaign.destroy');
        
        Route::get('/phishing-campaign/download-data/{phishingCampaign}', [PhishingCampaignController::class, 'downloadDataCSV'])->name('phishing-campaign.download-data-csv');
        Route::get('/phishing-campaign/download-all-data', [PhishingCampaignController::class, 'downloadAllDataCSV'])->name('phishing-campaign.download-all-data-csv');

        // Phishing email attributes
        Route::get('/phishing-campaign/option', [PhishingCampaignController::class, 'option'])->name('phishing-campaign.option');
        Route::post('/phishing-campaign/context', [PhishingContextController::class, 'create'])->name('context.create');
        Route::delete('/phishing-campaign/context/{context}', [PhishingContextController::class, 'destroy'])->name('context.destroy');

        Route::post('/phishing-campaign/persuasion', [PhishingPersuasionController::class, 'create'])->name('persuasion.create');
        Route::delete('/phishing-campaign/persuasion/{persuasion}', [PhishingPersuasionController::class, 'destroy'])->name('persuasion.destroy');

        Route::post('/phishing-campaign/emotional-trigger', [PhishingEmotionalTriggerController::class, 'create'])->name('emotional-trigger.create');
        Route::delete('/phishing-campaign/emotional-trigger/{emotionalTrigger}', [PhishingEmotionalTriggerController::class, 'destroy'])->name('emotional-trigger.destroy');

        // Digital twins - Authors: Gioele Giannico, Francesco Baldi
        Route::get('/digital-twin', [DigitalTwinController::class, 'index'])->name('digital-twin.index');

        Route::get('/digital-twin/new', [DigitalTwinController::class, 'new'])->name('digital-twin.new');
        Route::post('/digital-twin/create', [DigitalTwinController::class, 'create'])->name('digital-twin.create');

        Route::get('/digital-twin/detail/{digitalTwin}', [DigitalTwinController::class, 'details'])->name('digital-twin.details');
        Route::get('/digital-twin/duplicate/{digitalTwin}', [DigitalTwinController::class, 'duplicate'])->name('digital-twin.duplicate');
        Route::delete('/digital-twin/delete/{digitalTwin}', [DigitalTwinController::class, 'destroy'])->name('digital-twin.destroy');

        Route::get('/digital-twin/fake-users', [DigitalTwinController::class, 'fakeUsers'])->name('digital-twin.fake-users');
        Route::post('/digital-twin/fake-users', [DigitalTwinController::class, 'redirectFakeUsers'])->name('digital-twin.redirect-fake-users');

        Route::get('/digital-twin/select-users', [DigitalTwinController::class, 'selectUsers'])->name('digital-twin.select-users');


        // Fake users - Authors: Gioele Giannico, Francesco Baldi
        Route::post('/fake-user/create', [FakeUserController::class, 'create'])->name('fake-user.create');
        Route::delete('/fake-user/delete/{fakeUserId}', [FakeUserController::class, 'destroy'])->name('fake-user.destroy');

        // Ethical phishing campaigns - Author: Francesco Maggio
        Route::get('/ethical-phishing-campaign', [EthicalPhishingCampaignController::class, 'index'])->name('ethical-phishing-campaign.index');

        Route::get('/ethical-phishing-campaign/new', [EthicalPhishingCampaignController::class, 'new'])->name('ethical-phishing-campaign.new');
        Route::post('/ethical-phishing-campaign/create', [EthicalPhishingCampaignController::class, 'create'])->name('ethical-phishing-campaign.create');
        
        Route::get('/ethical-phishing-campaign/{phishingCampaign}/select-users', [EthicalPhishingCampaignController::class, 'selectUsers'])->name('ethical-phishing-campaign.select-users');
        Route::post('/ethical-phishing-campaign/{phishingCampaign}/assign-users', [EthicalPhishingCampaignController::class, 'assignUsers'])->name('ethical-phishing-campaign.assign-users');

        Route::post('/ethical-phishing-campaign/change-state/{phishingCampaign}/{state}', [EthicalPhishingCampaignController::class, 'changeState'])->name('ethical-phishing-campaign.change-state');
        Route::get('/ethical-phishing-campaign/analyse/{phishingCampaign}', [EthicalPhishingCampaignController::class, 'analyse'])->name('ethical-phishing-campaign.analyse');
        Route::get('/ethical-phishing-campaign/detail/{phishingCampaign}', [EthicalPhishingCampaignController::class, 'details'])->name('ethical-phishing-campaign.details');
        Route::get('/ethical-phishing-campaign/duplicate/{phishingCampaign}', [EthicalPhishingCampaignController::class, 'duplicate'])->name('ethical-phishing-campaign.duplicate');
        Route::delete('/ethical-phishing-campaign/delete/{phishingCampaign}', [EthicalPhishingCampaignController::class, 'destroy'])->name('ethical-phishing-campaign.destroy');

    });

    Route::middleware(['role:Admin,Evaluator'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['role:User'])->group(function () {
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');
        Route::get('/questionnaires-campaign/{questionnaireCampaign}', [QuestionnaireCampaignController::class, 'joinQuestionnaireCampaign'])->name('questionnaires-campaign.questionnaire-campaign-join');

        // HAIS
        Route::post('/hais', [HAISController::class, 'create'])->name('hais.create');
        Route::get('/hais/answer/{user}/{questionnaireCampaign}/{questionnaire}', [HAISController::class, 'answer'])->name('hais.answer');

        // DAMOCLES
        Route::post('/damocles', [DAMOCLESController::class, 'create'])->name('damocles.create');
        Route::get('/damocles/answer/{user}/{questionnaireCampaign}/{questionnaire}', [DAMOCLESController::class, 'answer'])->name('damocles.answer');
    });

    Route::middleware(['role:Evaluator,User'])->group(function () {
        Route::get('/questionnaires-campaign', [QuestionnaireCampaignController::class, 'index'])->name('questionnaires-campaign.index');

        // HAIS
        Route::get('/hais/{questionnaireCampaign}/{questionnaire}', [HAISController::class, 'index'])->name('hais.index');

        // DAMOCLES
        Route::get('/damocles/{questionnaireCampaign}/{questionnaire}', [DAMOCLESController::class, 'index'])->name('damocles.index');
    });
});

// Endpoint for the link opened in the email
Route::get('/opened/{email}/{user}', [PhishingCampaignController::class, 'opened'])->name('phishing.campaign.opened');

// Endpoint for the link clicked in the email
Route::get('/clicked/{email}/{user}', [PhishingCampaignController::class, 'clicked'])->name('phishing.campaign.clicked');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
