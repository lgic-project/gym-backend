<div class="form-group">
    <label for="name">Name</label>
    <input type="text" class="form-control" placeholder="Name" name="name"
        value="{{ old('name', isset($user) ? $user->name : '') }}">
    @error('name')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input type="email" class="form-control" placeholder="Email" name="email"
        value="{{ old('email', isset($user) ? $user->email : '') }}">
    @error('email')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="password">Password</label>
    <input type="password" class="form-control" placeholder="Password" name="password">
    @error('password')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="user_role">Role</label>
    <select name="user_role" class="form-control" id="user_role" onchange="checkRole()">
        @foreach (App\Models\User::ROLES as $key => $role)
            <option value="{{ $key }}" {{ old('user_role', isset($user) ? $user->user_role : '') == $key ? 'selected' : '' }}>
                {{ $role }}
            </option>
        @endforeach
    </select>
    @error('user_role')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div class="form-group">
    <label for="photo">Photo</label>
    @if (isset($user) && $user->getFirstMedia())
        <img src="{{ $user->getFirstMediaUrl() }}" alt="{{ $user->name }}" width="100" height="100">
    @endif
    <input type="file" name="photo" class="form-control">
    @error('photo')
        <p class="text-danger">{{ $message }}</p>
    @enderror
</div>

<div id="trainer-info" style="display: none;">
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" rows="4" class="form-control">{{ old('description', isset($user) ? $user->description : '') }}</textarea>
        @error('description')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group">
        <label for="cost_per_month">Cost Per Month</label>
        <input type="text" class="form-control" placeholder="Cost Per Month" name="cost_per_month"
            value="{{ old('cost_per_month', isset($user) ? $user->cost_per_month : '') }}">
        @error('cost_per_month')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
    <div class="form-group">
        <label for="experience">Experience</label>
        <textarea name="experience" id="experience" rows="4" class="form-control">{{ old('experience', isset($user) ? $user->experience : '') }}</textarea>
        @error('experience')
            <p class="text-danger">{{ $message }}</p>
        @enderror
    </div>
</div>

<script>
    function checkRole() {
        var role = document.getElementById('user_role').value;
        var trainerInfo = document.getElementById('trainer-info');
        if (role == 2) {
            trainerInfo.style.display = "block";
        } else {
            trainerInfo.style.display = "none";
        }
    }

    window.onload = function() {
        checkRole();
    }
</script>
