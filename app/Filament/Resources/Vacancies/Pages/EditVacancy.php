<?php

namespace App\Filament\Resources\Vacancies\Pages;

use App\Filament\Resources\Vacancies\VacancyResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditVacancy extends EditRecord
{
    protected static string $resource = VacancyResource::class;

    /**
     * Flag untuk menandai apakah user sudah konfirmasi unpaid
     */
    public bool $confirmedUnpaid = false;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->successNotificationTitle('Deleted!'),
        ];
    }

    /**
     * Override method save untuk menambahkan modal confirmation
     */
    public function save(bool $shouldRedirect = true, bool $shouldSendSavedNotification = true): void
    {
        $data = $this->form->getState();

        // Cek apakah salary kosong dan belum dikonfirmasi
        if (empty($data['salary_min']) && empty($data['salary_max']) && !$this->confirmedUnpaid) {
            // Tampilkan modal confirmation menggunakan mountAction
            $this->mountAction('confirmUnpaid');
            return;
        }

        // Reset flag dan lanjutkan save
        $this->confirmedUnpaid = false;
        parent::save($shouldRedirect, $shouldSendSavedNotification);
    }

    /**
     * Method untuk konfirmasi unpaid dari modal
     */
    public function confirmUnpaid(): Action
    {
        return Action::make('confirmUnpaid')
            ->label('Job Unpaid')
            ->modalHeading('Job Unpaid')
            ->modalDescription('Jika kolom field salary/gaji tidak diisi maka dianggap unpaid')
            ->modalSubmitActionLabel('Saya Mengerti')
            ->action(function () {
                $this->confirmedUnpaid = true;
                $this->save();
            });
    }

    /**
     * Mutasi data sebelum save:
     * - Jika salary kosong, set ke null (akan tampil sebagai "Unpaid")
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (empty($data['salary_min'])) {
            $data['salary_min'] = null;
        }
        if (empty($data['salary_max'])) {
            $data['salary_max'] = null;
        }
        return $data;
    }

    public function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Updated!';
    }
}
