<?php

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Filament\Resources\JobResource\RelationManagers;
use App\Models\Job;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use App\Models\Company;
use App\Models\Category;
use App\Models\Skill;
use App\Models\Location;
use Filament\Forms\Components\RichEditor;
use Illuminate\Support\Str;
use Filament\Tables\Columns\BadgeColumn;


class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        Select::make('company_id')
                            ->label('Company')
                            ->options(Company::all()->pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Forms\Components\TextInput::make('title')
                            ->reactive()->afterStateUpdated(fn ($state, callable $set) => $set('unique_id', Str::slug($state)))
                            ->required()
                            ->maxLength(255),
                        RichEditor::make('description')
                            ->required()
                            ->maxLength(65535),
                        Select::make('category')
                            ->label('Category')
                            ->options(Category::all()->pluck('name', 'id'))
                            ->searchable()
                            ->multiple()
                            ->required(),
                        Forms\Components\TextInput::make('apply_url')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('apply_count')
                            ->default(0)
                            ->disabled()
                            ->required(),
                        Forms\Components\TextInput::make('position')
                            ->required(),
                        Forms\Components\TextInput::make('salary'),
                        Forms\Components\Select::make('locations')
                            ->options(Location::all()->pluck('name', 'id'))
                            ->searchable()
                            ->multiple()
                            ->label('Location')
                            ->required(),
                        Forms\Components\Select::make('skills')
                            ->label('Skills')
                            ->options(Skill::all()->pluck('name', 'id'))
                            ->searchable()
                            ->multiple(),
                        Forms\Components\TextInput::make('unique_id')
                            ->required()
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('seo_image')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('seo_title')
                            ->maxLength(255),
                        Forms\Components\Textarea::make('seo_keywords')
                            ->maxLength(65535),
                        Forms\Components\Textarea::make('seo_description')
                            ->maxLength(65535),
                        Forms\Components\TextInput::make('source')->default('admin')->disabled()
                            ->maxLength(255),
                        Forms\Components\Select::make('status')
                            ->options([
                                1 => 'Pending',
                                2 => 'Approve',
                                3 => 'Rejected',
                            ])
                            ->required(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),
                Tables\Columns\TextColumn::make('apply_count'),
                Tables\Columns\TextColumn::make('unique_id'),
                Tables\Columns\TextColumn::make('source'),
                BadgeColumn::make('status')
                    ->enum([
                        1 => 'Pending',
                        2 => 'Approved',
                        3 => 'Rejected',
                    ])->colors([
                        'primary',
                        'secondary' => 1,
                        'success' => 2,
                        'danger' => 3,
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'create' => Pages\CreateJob::route('/create'),
            'edit' => Pages\EditJob::route('/{record}/edit'),
        ];
    }
}
