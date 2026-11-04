<?php

namespace App\Filament\Resources\Questions\RelationManagers;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use App\Models\QuestionAnswer;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification as FilamentNotification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('Officer Answers');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('body')
                    ->label(__('Response / Answer Content'))
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('Answer Status'))
                    ->options(AnswerStatus::class)
                    ->default(AnswerStatus::Published->value)
                    ->required(),
                Toggle::make('is_published')
                    ->label(__('Publish to Public'))
                    ->helperText(__('If enabled, this question & answer can be viewed on the public portal'))
                    ->default(true),
                DateTimePicker::make('answered_at')
                    ->label(__('Time Answered'))
                    ->default(now()),
                Hidden::make('user_id')
                    ->default(fn () => auth()->id()),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Responding Officer'))
                    ->weight('bold'),
                TextColumn::make('body')
                    ->label(__('Answer Summary'))
                    ->html()
                    ->limit(60),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (AnswerStatus $state): string => $state->getColor()),
                IconColumn::make('is_published')
                    ->label(__('Published'))
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->label(__('Date Answered'))
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label(__('Write Answer'))
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['user_id'] = auth()->id();
                        $data['answered_at'] = now();

                        return $data;
                    })
                    ->after(function (QuestionAnswer $record) {
                        $question = $record->question;
                        if ($record->is_published || $record->status === AnswerStatus::Published) {
                            $question->update(['status' => QuestionStatus::Answered]);

                            // Send email notification to citizen
                            if ($question->email) {
                                try {
                                    Notification::route('mail', $question->email)
                                        ->notify(new QuestionAnsweredNotification($record));
                                } catch (\Throwable $e) {
                                    // Fail gracefully if mail server is not configured
                                }
                            }

                            FilamentNotification::make()
                                ->title(__('Answer Saved Successfully'))
                                ->body(__('Question status has been updated to Answered.'))
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->after(function (QuestionAnswer $record) {
                        $question = $record->question;
                        if ($record->is_published || $record->status === AnswerStatus::Published) {
                            $question->update(['status' => QuestionStatus::Answered]);
                        }
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
