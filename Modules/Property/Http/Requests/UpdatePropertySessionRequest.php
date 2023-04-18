<?php

namespace Modules\Property\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class UpdatePropertySessionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->request->get('step') == 1) {
            return [
                'user_id'                   => 'required',
                'state_id'                     => 'required',
                'city_id'                      => 'required',
                'area_id'                      => 'required',
                'map_location'                  => 'required|max:250',
                'full_address'                   => 'required',
                'lat'                       => 'required',
                'long'                      => 'required',
            ];
        } elseif ($this->request->get('step') == 2) {
            return [
                // 'total_seats'             => 'required',
                // 'rented_seats'            => 'required',
                // 'total_floors'            => 'required',
                'property_name'           => 'required|max:250',
                'available_fors'           => 'required',
                // 'security_deposit_amount' => 'required',
                'property_description'    => 'required',
                'user_id'                 => 'required',
            ];
        } elseif ($this->request->get('step') == 3) {
            return [
                'user_id'    => 'required',
            ];
        } elseif ($this->request->get('step') == 4) {
            return [
                'payment_type' => 'required',
                'user_id' => 'required',
                'tin_number'         => 'nullable|numeric|digits:11',
                // 'account_number'     => ['required','numeric','digits:10'],
                // 'bank_id'            => 'required',
            ];
        } else {
        }
    }

    public function withValidator(Validator $validator)
    {
        $step = $validator->getData()['step'] ?? '';
        $property_type_id = $validator->getData()['property_type_id'] ?? '';
        $total_room_rented = $validator->getData()['single']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['single']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['double']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['double']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['triple']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['triple']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['quadruple']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['quadruple']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['standard']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['standard']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['deluxe']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['deluxe']['non_ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['suite']['ac_rented_seats'] ?? 0;
        $total_room_rented += $validator->getData()['suite']['non_ac_rented_seats'] ?? 0;


        $total_room_total = $validator->getData()['single']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['single']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['double']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['double']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['triple']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['triple']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['quadruple']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['quadruple']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['standard']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['standard']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['deluxe']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['deluxe']['non_ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['suite']['ac_total_seats'] ?? 0;
        $total_room_total += $validator->getData()['suite']['non_ac_total_seats'] ?? 0;


        $validator->after(
            function ($validator) use ($step, $total_room_rented, $property_type_id, $total_room_total) {
                if ($step == 2 && in_array($property_type_id, [1, 3, 4])) {
                    if ((int)$validator->getData()['rented_seats'] > (int)$validator->getData()['total_seats']) {
                        $validator->errors()->add(
                            'total_seats',
                            'Rented seats cannot be more than Total seats!'
                        );
                    }

                    if (isset($validator->getData()['single']['ac_rented_seats']) && ((int)$validator->getData()['single']['ac_rented_seats']  > (int)$validator->getData()['single']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Single AC rented seats cannot be more than single AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['single']['non_ac_rented_seats']) && ((int)$validator->getData()['single']['non_ac_rented_seats'] > (int)$validator->getData()['single']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Single  Non-AC rented seats cannot be more than single Non-AC total seats!'
                        );
                    }


                    if (isset($validator->getData()['double']['ac_rented_seats']) && ((int)$validator->getData()['double']['ac_rented_seats'] > (int)$validator->getData()['double']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Double AC rented seats cannot be more than double AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['double']['non_ac_rented_seats']) && ((int)$validator->getData()['double']['non_ac_rented_seats'] > (int)$validator->getData()['double']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Double Non-AC rented seats cannot be more than double Non-AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['triple']['ac_rented_seats']) && ((int)$validator->getData()['triple']['ac_rented_seats'] > (int)$validator->getData()['triple']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Triple AC rented seats cannot be more than triple AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['triple']['non_ac_rented_seats']) && ((int)$validator->getData()['triple']['non_ac_rented_seats'] > (int)$validator->getData()['triple']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Triple Non-AC rented seats cannot be more than triple Non-AC total seats!'
                        );
                    }


                    if (isset($validator->getData()['quadruple']['ac_rented_seats']) && ((int)$validator->getData()['quadruple']['ac_rented_seats'] > (int)$validator->getData()['quadruple']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Quadruple AC rented seats cannot be more than quadruple AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['quadruple']['non_ac_rented_seats']) && ((int)$validator->getData()['quadruple']['non_ac_rented_seats'] > (int)$validator->getData()['quadruple']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Quadruple Non-AC rented seats cannot be more than quadruple Non-AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['standard']['ac_rented_seats']) && ((int)$validator->getData()['standard']['ac_rented_seats'] > (int)$validator->getData()['standard']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Standard AC rented seats cannot be more than standard AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['standard']['non_ac_rented_seats']) && ((int)$validator->getData()['standard']['non_ac_rented_seats'] > (int)$validator->getData()['standard']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Standard Non-AC rented seats cannot be more than standard Non-AC total seats!'
                        );
                    }


                    if (isset($validator->getData()['deluxe']['ac_rented_seats']) && ((int)$validator->getData()['deluxe']['ac_rented_seats'] > (int)$validator->getData()['deluxe']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Deluxe AC rented seats cannot be more than deluxe AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['deluxe']['non_ac_rented_seats']) && ((int)$validator->getData()['deluxe']['non_ac_rented_seats'] > (int)$validator->getData()['deluxe']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Deluxe Non-AC rented seats cannot be more than deluxe Non-AC total seats!'
                        );
                    }


                    if (isset($validator->getData()['suite']['ac_rented_seats']) && ((int)$validator->getData()['suite']['ac_rented_seats'] > (int)$validator->getData()['suite']['ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Suite AC rented seats cannot be more than suite AC total seats!'
                        );
                    }

                    if (isset($validator->getData()['suite']['non_ac_rented_seats']) && ((int)$validator->getData()['suite']['non_ac_rented_seats'] > (int)$validator->getData()['suite']['non_ac_total_seats'])) {
                        $validator->errors()->add(
                            'total_seats',
                            'Suite Non-AC rented seats cannot be more than suite Non-AC total seats!'
                        );
                    }

                    if ((int)$validator->getData()['rented_seats'] != (int)$total_room_rented) {
                        $validator->errors()->add(
                            'rented_seats',
                            'Rented seats of all rooms must be equal Rented seats!'
                        );
                    }


                    if ((int)$validator->getData()['total_seats'] < (int)$total_room_total) {
                        $validator->errors()->add(
                            'total_seats',
                            'Total seats of all rooms cannot be more than total seats!'
                        );
                    }
                }

                if ($step == 3) {
                    $youtube_url = $validator->getData()['video_url'];
                    if($youtube_url !=''){
                        if($this->youtubeURLFormat($youtube_url) == false){
                            $validator->errors()->add(
                                'video_url',
                                'incorrect youtube url format'
                            );
                        }
                    }
                }

            }
        );
    }

    // youtube url format
    public function youtubeURLFormat($youtube_url)
    {
        return (bool) preg_match('/^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/',$youtube_url);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
