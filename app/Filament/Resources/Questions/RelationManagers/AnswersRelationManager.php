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
use Illuminate\Support\Facades\Notification;

class AnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Jawaban Petugas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                RichEditor::make('body')
                    ->label('Isi Tanggapan / Jawaban')
                    ->required()
                    ->columnSpanFull(),
                Select::make('status')
                    ->label('Status Jawaban')
                    ->options(AnswerStatus::class)
                    ->default(AnswerStatus::Published->value)
                    ->required(),
                Toggle::make('is_published')
                    ->label('Publikasikan ke Publik')
                    ->helperText('Jika diaktifkan, pertanyaan & jawaban ini dapat dilihat di portal publik')
                    ->default(true),
                DateTimePicker::make('answered_at')
                    ->label('Waktu Dijawab')
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
                    ->label('Petugas Penjawab')
                    ->weight('bold'),
                TextColumn::make('body')
                    ->label('Ringkasan Jawaban')
                    ->html()
                    ->limit(60),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (AnswerStatus $state): string => $state->getColor()),
                IconColumn::make('is_published')
                    ->label('Dipublikasi')
                    ->boolean(),
                TextColumn::make('answered_at')
                    ->label('Tanggal Dijawab')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tulis Jawaban')
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
                                ->title('Jawaban Berhasil Disimpan')
                                ->body('Status pertanyaan telah diperbarui menjadi Sudah Dijawab.')
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
