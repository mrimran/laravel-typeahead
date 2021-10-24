<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-800 sm:items-center py-4 sm:pt-0">
            <div class="row country_area">
                <div class="col-md-6 offset-md-3 mt-5">
                    <h5>Laravel + Typeahead</h5>
                    <input id="country" type="text" placeholder="Country" class="form-control typeahead country" attrtype="country">
                </div>
            </div>
            <div class="row city_area">
                <div class="col-md-6 offset-md-3 mt-5">
                    <input id="city" type="text" placeholder="City" class="form-control typeahead city" attrtype="city">
                </div>
            </div>
            <input id="selected_country" type="hidden" name="selected_country" value="" />
            <input id="selected_city" type="hidden" name="selected_city" value="" />
            <input id="selection_type" type="hidden" name="selection_type" value="" />
            <input id="query_type" type="hidden" name="query_type" value="" />
            <input id="auto_trigger" type="hidden" name="auto_trigger" value="" />
            <input id="auto_trigger_from" type="hidden" name="auto_trigger_from" value="" />
        </div>
    </body>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" ></script>

    <script type="text/javascript">
        var path = "{{ url('autocomplete') }}";
        $('input.typeahead').typeahead({
            name: 'autocomp',
            freeInput: false,
            classNames: {
                input: 'autocomp-typeahead-input',
                hint: 'autocomp-typeahead-hint',
                selectable: 'autocomp-typeahead-selectable'
            },
            source:  function (query, process) {
                // console.log('called', item);
                var queryType = $('#query_type').val();
                var autoTrigger = $('#auto_trigger').val();
                if(queryType.length <= 1) {
                    return false;
                }

                return $.get(path, {
                    query: $(queryType).val(),
                    type: queryType,
                    selected_country: $('#selected_country').val(),
                    selected_city: $('#selected_city').val(),
                    auto_trigger: autoTrigger
                }, function (data) {
                    console.log('autoTrigger', autoTrigger);
                    if(autoTrigger == '1') {
                        prepareCustomSelectionOptions(data, queryType);
                    }
                    return process(data);
                });
            },
            activated: function (item) {
                console.log('on active....', item);
            },
            // updater: function name(item) {
            //     console.log('updater item', item);
            // },
            afterSelect: function(item) {
                customAfterSelect(item, this, '');
            }
        });

        function prepareCustomSelectionOptions(responseData, queryType) {
            if(responseData.length >= 1) {
                $(`.${queryType}_area`).find('.dropdown-menu').html('');
            }
            responseData.forEach(str => {
                str = str.replace(/\s/g, '');
                // console.log('aya', $('#city_' + str), $('#city_' + str).length);
                var id = `${queryType}_${str}`;
                var areaClass = `${queryType}_area`;
                if($(`#${id}`).length <= 0 || $(`#${id}`) == 'undefined') {
                    $(`.${areaClass}`).find('.dropdown-menu')
                        .append('<li id="' + id + '" class=""><a class="dropdown-item" href="#" onclick="customAfterSelect(\'' + str + '\', $(\'.' + areaClass + '\'), \'' + queryType + '\')" role="option">' + str + '</a></li>');
                }
            });
        }

        function customAfterSelect(item, thisObj, sourceElmType) {
            var passedStoredElmType = sourceElmType;
            console.log('item', item, sourceElmType);
            if(item.length <= 0 || item == 'undefined') {
                return;
            }
            // $('hiddenInputElement').val(map[item].id);
            // return item;
            var selectedVal = item;
            sourceElmType = sourceElmType.length == 0 ? $($(thisObj).get(0).$element).attr('attrtype') : sourceElmType;
            console.log('#selected_' + sourceElmType, selectedVal);
            $('#selected_' + sourceElmType).val(selectedVal).change();
            $('#selection_type').val(sourceElmType).change();
            // $('#' + sourceElmType).typeahead("");
            // $('#city').trigger('keyup').change();

            if(sourceElmType == 'country' && $('#auto_trigger').val() != '1') {
                $('#query_type').val('city');
                $('#auto_trigger').val('1');
                $('#auto_trigger_from').val('country');
                $('#selected_city').val('');
                $("#city.typeahead").eq(0).val('all').trigger("input").val('').trigger('input').focus();
                $("#city.typeahead").focus().typeahead('val','').focus();
                $('.city_area').find('.dropdown-menu').show();
            } else if(sourceElmType == 'city' && $('#auto_trigger').val() != '1') {
                $('#query_type').val('country');
                $('#auto_trigger').val('1');
                $('#auto_trigger_from').val('city');
                $('#selected_country').val('');
                $("#country.typeahead").eq(0).val('all').trigger("input").val('').trigger('input').focus();
                $('.country_area').find('.dropdown-menu').show();
                $("#country.typeahead").focus().typeahead('val','').focus();
            }

            if($('#' + sourceElmType).val() == 0) {
                console.log('setting upt the valu');
                // making sure value gets updated on select
                setTimeout(() => {
                    $('#' + sourceElmType).val(item);
                }, 50);
            }

            // $("#city.typeahead").typeahead('val', '')
            // $("#city.typeahead").focus().typeahead('val',theVal).focus();
            // document.getElementById('selected_' + sourceElmType).value = selectedVal;
            // console.log('changed', $('#selected_' + sourceElmType).val());
        }

        $(document).ready(function() {
            $('#auto_trigger').val('');
            $('#auto_trigger_from').val('');

            $('input.typeahead').keypress(function() {
                // console.log('typeahead', $(this).val());
                $('#query_type').val($(this).attr('attrtype')).change();

                if($(this).attr('attrtype') == $('#auto_trigger_from').val()) {
                    $('#auto_trigger').val('');
                }
            });

            $('#selected_country').change(function() {
                // $('.typeahead.dropdown-menu').remove();
                console.log('country selected', $(this).val());
            });

            $('#selected_city').change(function() {
                // $('.typeahead.dropdown-menu').remove();
                console.log('city selected', $(this).val());
            });

            $('#selection_type').change(function() {
                console.log('selection type changed', $(this).val());
            });

            $('#query_type').change(function() {
                console.log('query type changed', $(this).val());
            });
        });

        // $('.typeahead_country').on('typeahead:autocomplete', function(evt, item) {
        //     // do what you want with the item here
        //     console.log(item);
        // });
    </script>
</html>
