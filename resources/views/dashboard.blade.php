<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid p-4">
        <div class="">
            <form method="POST" action="{{route('guest-data')}}">
                @csrf
                <div class="form-row">
                    <b class="m-2">Period: </b>
                    <input type="date" id="" name="start-date" class="m-2">

                    <input type="date" id="" name="end-date" class="m-2">

                </div>
                <div class="form-row">
                    <b class="m-2">Search: </b>
                    <input type="text" id="" name="keyword" class="m-2 w-25" placeholder="Search by name, phone, email, & nid">


                </div>

                <button type="submit" class="btn btn-primary">Refresh</button>
            </form>
        </div>
        <div class="mt-4">
            @if(isset($msg))
            <h4>Showing results for "{{ $msg }}".</h4>
            @endif
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">First Name</th>
                        <th scope="col">Last Name</th>
                        <th scope="col">Arrival Date</th>
                        <th scope="col">Room</th>
                        <th scope="col">Hotel</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">NID</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Occupation</th>
                        <th scope="col">Nationality</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($guests as $item)
                    <tr>
                        <th scope="row">{{$item['id']}}</th>
                        <td>{{$item['first_name']}}</td>
                        <td>{{$item['last_name']}}</td>
                        <td>{{$item['arrival_date']}}</td>
                        <td>{{$item['room']['room_name']}}</td>
                        <td>{{$item['hotel']['name_en']}}</td>
                        <td>{{$item['email']}}</td>
                        <td>{{$item['phone']}}</td>
                        <td>{{$item['nid_no']}}</td>
                        <td>{{$item['dob']}}</td>
                        <td>{{$item['occupation']}}</td>
                        <td>{{$item['nationality']}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $guests->onEachSide(5)->links() }}
        </div>
    </div>
</body>

</html>