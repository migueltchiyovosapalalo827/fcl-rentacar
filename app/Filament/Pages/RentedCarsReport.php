<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\RentedCars\CurrentlyRentedCarsWidget;
use App\Filament\Widgets\RentedCars\RentedCarsDetailWidget;
use App\Filament\Widgets\RentedCars\RentedCarsRankingWidget;
use App\Filament\Widgets\RentedCars\RentedCarsStatsWidget;
use App\Reports\RentedCarsReportQuery;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Dashboard\Concerns\HasFiltersForm;
use Filament\Pages\Page;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RentedCarsReport extends Page
{
    use HasFiltersForm;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Carros Alugados';

    protected static ?string $title = 'Relatório de Carros Alugados';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?int $navigationSort = 11;

    public function getSubheading(): ?string
    {
        return 'Ocupação atual, ranking de viaturas e detalhe dos alugueres no período selecionado.';
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            RentedCarsStatsWidget::class,
            CurrentlyRentedCarsWidget::class,
            RentedCarsRankingWidget::class,
            RentedCarsDetailWidget::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return 1;
    }

    public function filtersForm(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Período do relatório')
                    ->description('Os cartões e as tabelas atualizam automaticamente ao alterar as datas.')
                    ->icon(Heroicon::OutlinedCalendarDays)
                    ->schema([
                        DatePicker::make('from')
                            ->label('De')
                            ->default(now()->startOfMonth()->toDateString())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection(),
                        DatePicker::make('until')
                            ->label('Até')
                            ->default(now()->toDateString())
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->closeOnDateSelection(),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getFiltersFormContentComponent(),
                $this->getWidgetsContentComponent(),
            ]);
    }

    public function getFiltersFormContentComponent(): Component
    {
        return EmbeddedSchema::make('filtersForm');
    }

    public function getWidgetsContentComponent(): Component
    {
        return Grid::make($this->getColumns())
            ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets()));
    }

    protected function getHeaderActions(): array
    {
        return [
            // Action::make('print')
            //     ->label('Imprimir')
            //     ->icon('heroicon-o-printer')
            //     ->color('gray')
            //     ->outlined()
            //     ->alpineClickHandler('window.print()'),
            Action::make('exportCsv')
                ->label('Exportar CSV')
                ->icon('heroicon-o-arrow-down-tray')
                ->action('exportCsv'),
        ];
    }

    public function exportCsv(): StreamedResponse
    {
        [$from, $until] = RentedCarsReportQuery::period($this->filters);
        $rentals = RentedCarsReportQuery::overlappingRentalsQuery($from, $until)
            ->orderByDesc('start_date')
            ->get();

        $filename = 'relatorio-carros-alugados-'.now()->format('Y-m-d').'.csv';

        return response()->streamDownload(function () use ($rentals): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Carro', 'Matrícula', 'Cliente', 'Início', 'Fim', 'Dias', 'Estado', 'Valor (AOA)']);

            foreach ($rentals as $reservation) {
                $days = max(1, (int) ceil($reservation->start_date->floatDiffInDays($reservation->end_date)));
                fputcsv($handle, [
                    RentedCarsReportQuery::vehicleLabel($reservation),
                    $reservation->car->plate_number ?? '—',
                    $reservation->client->name ?? '—',
                    optional($reservation->start_date)->format('d/m/Y H:i'),
                    optional($reservation->end_date)->format('d/m/Y H:i'),
                    $days,
                    RentedCarsReportQuery::statusLabel($reservation->status),
                    number_format((float) $reservation->total_amount, 2, ',', ''),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
