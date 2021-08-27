<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  {{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
  <title>Jobs</title>

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>
<body>
  <style>
    #style-search-form input {
      box-shadow: none;
    }
    .title {
      color: blue;
      /*text-decoration: none;*/
    }
    .ui-autocomplete { max-height: 150px; overflow-y: scroll; overflow-x: hidden;}
    i {
      cursor: pointer;
    }
    .scroll {
      overflow-x: hidden;
      overflow-y: scroll;
      height: 500px;
      border: 1px solid grey;
    }

    .scroll ul {
      padding: 10px;
      list-style: none;
    }
  </style>
  
  <div class="container py-2">
    @yield('content')
  </div>

  <script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.20.0/axios.min.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script>
    $('#autocomplete').autocomplete({
      source: function( request, response ) {
         $.ajax({
          url: '/places',
          dataType: 'json',
          data: {
            query: request.term
          },
          success: function(data) {

            response($.map(data['places'], function(item){
              return {
                value: `${item.state} ${item.city}`,
              }
            }));

            // response($.map(data['places'].slice(0, 5), function(item){
            //   return {
            //     value: `${item.state} ${item.city}`,
            //   }
            // }));

            // var results = $.ui.autocomplete.filter(data['places'], request.term);
        
            // response(results.slice(0, 10));
          },
        });
      }
    });

    $('#country-form').submit(async function(e) {
      e.preventDefault();

      const route = $(this).attr("action");
      const name = $(this).find('input').last().val();
      const _token = $(this).find('input').val();

      const dataPost = { name : name };
      //const config = { headers: { 'X-CSRF-TOKEN': _token } };

      console.log(route);
      console.log(dataPost);
      //console.log(config);
      const response = await axios.post(route, dataPost);
      const data = response['data'];
      console.log(response);
      $('#country-list').prepend(`
        <li class="py-1">
          ${ data.country.name }
          <i data-id="${ data.country.id_country }" class="float-right fas fa-home text-success"></i>
        </li>
      `)
    });

    $('#country-list').on('click', 'li i', async function() {
      console.log(this);
      const route = $('#country-list').data('route');
      const id = $(this).data('id');
      //const config = { headers: { 'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content') } };

      console.log(route, id);

      const dataPost = { idCountry: id };

      const response = await axios.post(route, dataPost);
      console.log(response.data)
      $(this).toggleClass('fas fa-home fas fa-user text-success text-primary');
    });

    // $("[name='country']").on("change", function (e) {
    //   let idCountry = $(this).val();
    //   console.log(idCountry);
    //   window.location.href = window.location.origin + `/jobs/create?idCountry=${ idCountry }`;
    // });
    
    
    $('.js-input-search').click(function(e) {

      let input_title = $('.js-input-title').val();
      let input_place = $('.js-input-place').val();

      if ( !input_title && !input_place ) {
        e.preventDefault();
        alert('Debe escribir al menos en una caja de texto');
      }

    });

    $('#js-select-countries').change(async function() {
      $('#js-select-cities option').remove();
      $('#js-select-cities').val('');
      $('#js-select-cities').append(`<option selected disabled>Seleccione</option>`);

      $('#js-select-states option').remove();
      $('#js-select-states').val('');
      $('#js-select-states').append(`<option selected>Cargando...</option>`);
      $('#js-select-states').prop('disabled', true);
      
      const id_country = $(this).children("option:selected").val();
      const response = await axios.get(`/states?idCountry=${ id_country }`);
      const states = response.data['states'];

      $('#js-select-states option').remove();
      $('#js-select-states').append(`<option selected disabled>Seleccione</option>`);
      states.forEach(function(state) {
        const option = `<option value="${ state.id_state }">${ state.name }</option>`;
        $('#js-select-states').append(option);
      });
      $('#js-select-states').prop('disabled', false);

    });

    $('#js-select-states').change(async function() {
      $('#js-select-cities option').remove();
      $('#js-select-cities').val('');
      $('#js-select-cities').append(`<option selected>Cargando...</option>`);
      $('#js-select-cities').prop('disabled', true);

      const id_state = $(this).children("option:selected").val();
      const response = await axios.get(`/cities?idState=${ id_state }`);
      const cities = response.data['cities'];

      $('#js-select-cities option').remove();
      $('#js-select-cities').append(`<option selected disabled>Seleccione</option>`);
      cities.forEach(function(city) {
        var option = `<option value="${ city.id_city }">${ city.name }</option>`;
        $('#js-select-cities').append(option);
      });
      $('#js-select-cities').prop('disabled', false);
    });


  </script>

</body>
</html>
