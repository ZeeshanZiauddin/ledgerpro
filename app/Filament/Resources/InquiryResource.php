<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InquiryResource\Pages;
use App\Filament\Resources\InquiryResource\RelationManagers\InquiryPassengerRelationManager;
use App\Models\Inquiry;
use Carbon\Carbon;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Tables\Table;
use Icetalker\FilamentTableRepeater\Forms\Components\TableRepeater;
use Coolsam\FilamentFlatpickr\Forms\Components\Flatpickr;

class InquiryResource extends Resource
{
    protected static ?string $model = Inquiry::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->schema([
                        Group::make()
                            ->schema([
                                TextInput::make('inquiry_name')
                                    ->default(fn() => InquiryResource::generateInquiryName()) // Call the generateInquiryName method
                                    ->disabled() // Disable the field to prevent manual editing
                                    ->required()
                                    ->label('Inquiry No.'),
                                TextInput::make('user_name')
                                    ->label('User')
                                    ->default(fn($get) => auth()->user() ? auth()->user()->name : '')  // Show the logged-in user's name
                                    ->disabled()
                                    ->label('Owner'),
                                TextInput::make('user_id')
                                    ->default(fn() => auth()->id())  // Set the user_id to the logged-in user's ID
                                    ->hidden()
                                    ->required(),
                                TextInput::make('date')
                                    ->label('Date')
                                    ->default(Carbon::now()->format('d M Y'))
                                    ->disabled()
                                    ->required(),
                                Select::make('status')
                                    ->label('Status')
                                    ->options([
                                        'pending' => 'Pending',
                                        'canceled' => 'Canceled',
                                        'in_progress' => 'In Progress',
                                    ])
                                    ->required()
                                    ->default('pending')
                            ])
                            ->extraAttributes(['class' => 'gap-0.5'])  // Reduce gap between inputs
                            ->columnSpan(1),
                        Group::make()
                            ->schema([
                                TextInput::make('contact_name')
                                    ->required(),
                                TextInput::make('contact_email'),
                                TextInput::make('contact_mobile'),
                                TextInput::make('contact_address'),
                            ])
                            ->extraAttributes(['class' => 'gap-0.5'])
                            ->columnSpan(1),
                        Group::make()
                            ->schema([
                                TextInput::make('card_no'),
                                Flatpickr::make('option_date'),
                                TextInput::make('pnr'),
                                TextInput::make('filter_point'),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columns(3),

                TextArea::make('price_option')
                    ->columnSpanFull(),



                TableRepeater::make('items')
                    ->relationship('passengers')

                    ->schema([
                        Select::make('from_city_id')
                            ->label('City'), // Limit width for this select field
                        Select::make('from_country_id')
                            ->label('Country'),
                        Select::make('des_city_id')
                            ->label('City'),

                        Select::make('des_country_id')
                            ->label('Country'),

                        // Flatpickr::make('travel_dates')
                        //     ->range()
                        //     ->dateFormat('d M')
                        //     ->label('Dates')
                        //     ->extraAttributes(['class' => 'max-w-[200px]']),
                        TextInput::make('adults')
                            ->numeric()
                            ->extraAttributes(['style' => 'width: 60px;']),
                        TextInput::make('child')
                            ->numeric()
                            ->extraAttributes(['style' => 'width: 60px;']),
                        TextInput::make('infants')
                            ->numeric()
                            ->extraAttributes(['style' => 'width: 60px;']),
                        Select::make('flight_type')
                            ->label('Type')
                            ->options([
                                'return' => 'Return',
                                'one_way' => 'One Way',
                                'direct_one_way' => 'Direct One Way',
                                'direct_return' => 'Direct Return',
                            ])
                            ->default('one_way')
                            ->placeholder('Flight Type')
                            ->extraAttributes(['class' => 'max-w-[200px]']),
                        TextInput::make('airline')
                            ->label('Preferred Airline')
                            ->extraAttributes(['class' => 'max-w-[200px]']),
                    ])
                    ->reorderable()
                    ->collapsible()
                    ->columnSpan('full'),

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {

        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('inquiry_name'),
                \Filament\Tables\Columns\TextColumn::make('user.name')->label('User'),
                \Filament\Tables\Columns\TextColumn::make('date')->date(),
                \Filament\Tables\Columns\TextColumn::make('status'),
                \Filament\Tables\Columns\TextColumn::make('owner_firstname'),
                \Filament\Tables\Columns\TextColumn::make('owner_lastname'),
            ])
            ->filters([
                // Add any filters here
            ])
            ->actions([
                \Filament\Tables\Actions\EditAction::make(),
            ])

            ->bulkActions([
                \Filament\Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            InquiryPassengerRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInquiries::route('/'),
            //'create' => Pages\CreateInquiry::route('/create'),
        ];
    }
    public static function generateInquiryName(): string
    {
        $latestInquiry = Inquiry::latest('id')->first(); // Get the latest inquiry
        $latestNumber = $latestInquiry ? (int) substr($latestInquiry->inquiry_name, 2) : 0; // Extract the number part and increment it
        $newNumber = str_pad($latestNumber + 1, 7, '0', STR_PAD_LEFT); // Increment and pad the number with leading zeros

        return 'QR' . $newNumber; // Prefix with "QR"
    }



}
