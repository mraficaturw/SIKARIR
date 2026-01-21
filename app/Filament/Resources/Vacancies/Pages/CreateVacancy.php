<?php

namespace App\Filament\Resources\Vacancies\Pages;

use App\Filament\Resources\Vacancies\VacancyResource;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;

class CreateVacancy extends CreateRecord
{
    protected static string $resource = VacancyResource::class;

    /**
     * Flag untuk menandai apakah user sudah konfirmasi unpaid
     */
    public bool $confirmedUnpaid = false;

    /**
     * Override tombol Create dengan modal confirmation untuk salary kosong
     */
    protected function getCreateFormAction(): Action
    {
        return Action::make('create')
            ->label(__('filament-panels::resources/pages/create-record.form.actions.create.label'))
            ->submit('create')
            ->keyBindings(['mod+s']);
    }

    /**
     * Override method create untuk menambahkan modal confirmation
     */
    public function create(bool $another = false): void
    {
        $data = $this->form->getState();

        // Cek apakah salary kosong dan belum dikonfirmasi
        if (empty($data['salary_min']) && empty($data['salary_max']) && !$this->confirmedUnpaid) {
            // Tampilkan modal confirmation menggunakan mountAction
            $this->mountAction('confirmUnpaid');
            return;
        }

        // Reset flag dan lanjutkan create
        $this->confirmedUnpaid = false;
        parent::create($another);
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
                $this->create();
            });
    }

    /**
     * Mutasi data sebelum create:
     * - Jika salary kosong, set ke null (akan tampil sebagai "Unpaid")
     */
    protected function mutateFormDataBeforeCreate(array $data): array
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
}
