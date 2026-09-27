<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ReviewsStatsWidget;
use App\Filament\Widgets\Satisfaction\RecentReviewsWidget;
use App\Filament\Widgets\Satisfaction\SatisfactionRatingsChartWidget;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\Widget;
use Filament\Widgets\WidgetConfiguration;

class SatisfactionReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $navigationLabel = 'Satisfação';

    protected static ?string $title = 'Relatório de Satisfação do Cliente';

    protected static string|\UnitEnum|null $navigationGroup = 'Relatórios';

    protected static ?int $navigationSort = 10;

    public function getSubheading(): ?string
    {
        return 'Análise das avaliações e da taxa de satisfação dos clientes.';
    }

    /**
     * @return array<class-string<Widget> | WidgetConfiguration>
     */
    public function getWidgets(): array
    {
        return [
            ReviewsStatsWidget::class,
            SatisfactionRatingsChartWidget::class,
            RecentReviewsWidget::class,
        ];
    }

    /**
     * @return int | array<string, ?int>
     */
    public function getColumns(): int | array
    {
        return 1;
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make($this->getColumns())
                    ->schema(fn (): array => $this->getWidgetsSchemaComponents($this->getWidgets())),
            ]);
    }
}
