<div>
    @if(session('success'))
        <div style="color:green">
            {{session('success')}}
        </div>
    @endif

    @if(session('error'))
    <div style="color:red">
        {{session('error')}}
    </div>
@endif

    <form action="{{route('otp.getLogin')}}" method="POST">
        @csrf
        <input type="hidden" name="user_id" value="{{$user_id}}">
        <label for="">
            OTP
        </label>
        <br>
        <input type="text" name="otp" value="{{old('otp')}}" placeholder="Enter OTP" required>
        <br>

        @error('otp')
            <strong style="color:red">{{$message}}</strong>
        @enderror

        <button type="submit">Login</button>
    </form>
</div>