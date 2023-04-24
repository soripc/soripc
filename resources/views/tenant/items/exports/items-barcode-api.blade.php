<!DOCTYPE html>
<html lang="es">
    <head>
    </head>
    <body>
        @if(!empty($record))
            <div class="" >
                <div class=" " >
                    <table class="table" width="100%" style="text-align: center; padding: 0px; margin: 0px; vertical-align: top;">
                        @php
                            function withoutRounding($number, $total_decimals) {
                                $number = (string)$number;
                                if($number === '') {
                                    $number = '0';
                                }
                                if(strpos($number, '.') === false) {
                                    $number .= '.';
                                }
                                $number_arr = explode('.', $number);

                                $decimals = substr($number_arr[1], 0, $total_decimals);
                                if($decimals === false) {
                                    $decimals = '0';
                                }

                                $return = '';
                                if($total_decimals == 0) {
                                    $return = $number_arr[0];
                                } else {
                                    if(strlen($decimals) < $total_decimals) {
                                        $decimals = str_pad($decimals, $total_decimals, '0', STR_PAD_RIGHT);
                                    }
                                    $return = $number_arr[0] . '.' . $decimals;
                                }
                                return $return;
                            }
                            // dd($record->description);
                        @endphp
                        <tr>
                            <td class="celda" style="text-align: center; padding: 0px; margin: 0px; font-size: 9px; vertical-align: top;">
                                <table>
                                    <tr>
                                        <td  class="text-center color" width="50%" style="border-right: 2px solid #000000">
                                            <span style="font-size: 20px">{{ $record->currency_type->symbol }}  </span> <span style="font-size: 23px"> {{ str_replace(",", "" , number_format( $record->sale_unit_price, 2)) }}</span>
                                            <br>
                                            <span style="font-size: 15px"> C/U</span>
                                        </td>
                                        <td  class="text-center color" width="50%">
                                            @if(sizeof($record->item_unit_types))
                                                @php
                                                    $record->item_unit_types[0]->price_default
                                                @endphp
                                                <span style="font-size: 20px">{{ $record->currency_type->symbol }} </span> <span style="font-size: 23px"> {{ str_replace(",", "" , number_format( $record->item_unit_types[0]->price2 , 2)) }}</span>
                                                <br>
                                                <span style="font-size: 15px"> {{ $record->item_unit_types[0]->description }}</span>
                                            @else
                                                <img src="{{ public_path(auth()->user()->establishment->logo) }}" alt="logo" class="" style="width: 80px;">
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td  class="text-center" colspan="2" style="border-bottom: 2px solid #000000; font-size: 15px">{{ $record->name ? $record->name : $record->description }}</td>
                                    </tr>
                                    <tr>
                                        @if(sizeof($record->item_unit_types))
                                            <td  class="text-center" width="50%">
                                                <img src="{{ public_path(auth()->user()->establishment->logo) }}" alt="logo" class="" style="width: 60px;">
                                            </td>
                                            <td  class="text-center" width="50%">
                                                @php
                                                    $colour = [0,0,0];
                                                    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                    echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 2, 80, $colour)) . '">';
                                                @endphp
                                                <div style="font-size: 10px">{{$record->barcode}}</div>
                                            </td>
                                        @else
                                        <td  class="text-center" colspan="2">
                                            @php
                                                $colour = [0,0,0];
                                                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                                                echo '<img style="width:110px; max-height: 40px;" src="data:image/png;base64,' . base64_encode($generator->getBarcode($record->barcode, $generator::TYPE_CODE_128, 2, 80, $colour)) . '">';
                                            @endphp
                                            <div style="font-size: 10px">{{$record->barcode}}</div>
                                        </td>

                                        @endif

                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        @else
            <div>
                <p>No se encontraron registros.</p>
            </div>
        @endif
    </body>
</html>
