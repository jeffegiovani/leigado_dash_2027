<?php

namespace App\Filament\Dash\Resources\Faqs\Schemas;

use App\Models\FaqGroup;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Schemas;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Schemas\Components\Section::make('Perguntas Frequentes - FAQs')
                    ->description('Crie respostas que serão exibidas dentro do site nos respectivos produtos no qual foram agrupadas')
                    ->aside()
                    ->schema([
                        Forms\Components\Select::make('group_id')
                            ->options(
                                fn () => FaqGroup::query()->orderBy('title')->pluck('title', 'id')
                            )
                            ->required()
                            ->searchable()
                            ->columnSpanFull()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('group_title')
                                    ->required()
                                    ->maxLength(120)
                                    ->minLength(2)
                                    ->label('Título do Grupo'),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $data = [
                                    'title' => $data['group_title'],
                                    'slug' => str($data['group_title'])->replace(['/', '\\'], [' ', ' '])->slug('-', 'pt_BR', ['@' => '-']),
                                ];

                                return FaqGroup::query()->create($data)->getKey();
                            })
                            ->createOptionAction(
                                function (Action $action) {
                                    $action->modalWidth('xl')
                                        ->modalSubmitAction(function (Action $action) {
                                            $action->keyBindings(['enter'])->label('Criar e Usar Grupo');
                                        });
                                }
                            )
                            ->label('Grupo de Respostas'),

                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(160)
                            ->columnSpanFull()
                            ->label('Pergunta Frequente'),

                        Forms\Components\TextInput::make('slug')
                            // ->visibleOn('edit')
                            // ->required()
                            ->maxLength(120)
                            ->columnSpanFull()
                            ->hint(new HtmlString('<small>Deixe vazio pra gerar automaticamente</small>'))
                            ->label('URL de Acesso'),

                        Forms\Components\MarkdownEditor::make('content')
                            ->columnSpanFull()
                            ->disableToolbarButtons([
                                'attachFiles',
                                'codeBlock',
                                'heading',
                                // 'link',
                                'table',
                            ])
                            ->required()
                            ->label('Resposta da Pergunta Frequente'),
                    ]),
            ]);
    }
}
