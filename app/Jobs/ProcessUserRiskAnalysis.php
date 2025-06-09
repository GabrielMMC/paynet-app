<?php

namespace App\Jobs;

use App\Repositories\FinancialProfiles\UserFinancialProfileRepository;
use App\Services\FinancialProfiles\RiskAnalysisService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use App\DTOs\CreateFinancialProfileDTO;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\SerializesModels;
use App\Models\UserFinancialProfile;
use Illuminate\Support\Facades\Log;
use App\Enums\CpfSituationEnum;
use Illuminate\Bus\Queueable;
use App\DTOs\CepConsultDTO;
use App\Enums\CpfRiskEnum;
use App\Models\User;

class ProcessUserRiskAnalysis implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private CpfSituationEnum $cpfSituation,
        private CepConsultDTO $cepConsult,
        private User $user
    ) {}

    public function handle(RiskAnalysisService $riskService): void
    {
        $state = $this->user->address->state;
        $riskLevel = $riskService->analyze($this->cpfSituation, $state);


        $riskLevelPayload = $this->mountRiskLevelPayload($this->cpfSituation, $riskLevel, $this->user->id);
        $financialProfile = $this->bindRiskLevelToUser($riskLevelPayload);
        $pdf = $this->generateReportPdf();

        Log::channel('user-reports')->info('Relatório gerado para usuário', [
            'user_id' => $this->user->id,
            'risk_level' => $financialProfile->risk->description,
            'cpf_situation' => $financialProfile->situation->description,
            'pdf_size' => strlen($pdf),
            'fake_email' => $this->user->email
        ]);

        Storage::put("reports/{$this->user->id}.pdf", $pdf);
    }

    private function mountRiskLevelPayload(CpfSituationEnum $cpfSituation, CpfRiskEnum $riskLevel, int $userId): CreateFinancialProfileDTO
    {
        return new CreateFinancialProfileDTO(
            situationId: $cpfSituation,
            riskId: $riskLevel,
            userId: $userId
        );
    }

    private function bindRiskLevelToUser(CreateFinancialProfileDTO $createFinancialProfileDTO): UserFinancialProfile
    {
        return app(UserFinancialProfileRepository::class)
            ->createUserFinancialProfile($createFinancialProfileDTO);
    }

    private function generateReportPdf(): string
    {
        $pdf = app('dompdf.wrapper');

        return $pdf->loadView('reports.user_risk', [
            'user' => $this->user
        ])->output();
    }
}
