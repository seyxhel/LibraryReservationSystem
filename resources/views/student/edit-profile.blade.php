<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UNIARCHIVE | Edit Profile</title>
    <link rel="stylesheet" href="{{ asset('css/Student.EditProfile.css') }}">
</head>
<body>
    <h2>Edit Profile</h2>

    <!-- Form to Edit Profile -->
    <form id="edit-profile-form" method="POST" action="{{ route('student.updateProfile') }}">
        @csrf
        @method('PUT')

        <!-- School ID (Unchangeable) -->
        <div class="input-box">
            <label for="school_id">School ID:</label>
            <input 
                type="text" 
                class="input-field" 
                id="school_id" 
                name="school_id" 
                value="{{ Auth::guard('student')->user()->School_ID }}" 
                readonly>
            <i class="bx bx-id-card"></i>
        </div>

        <!-- Name Fields -->
        <div class="four-forms">
            <div class="input-box">
                <label for="lastname">Lastname:</label>
                <input 
                    type="text" 
                    class="input-field" 
                    id="lastname" 
                    name="last_name" 
                    value="{{ Auth::guard('student')->user()->LastName }}" 
                    placeholder="Enter new lastname">
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <label for="firstname">Firstname:</label>
                <input 
                    type="text" 
                    class="input-field" 
                    id="firstname" 
                    name="first_name" 
                    value="{{ Auth::guard('student')->user()->FirstName }}" 
                    placeholder="Enter new firstname">
                <i class="bx bx-user"></i>
            </div>
            <div class="input-box">
                <label for="middlename">Middlename:</label>
                <input 
                    type="text" 
                    class="input-field" 
                    id="middlename" 
                    name="middle_name" 
                    value="{{ Auth::guard('student')->user()->MiddleName }}" 
                    placeholder="Enter Middlename (optional)">
                <i class="bx bx-user"></i>
            </div>

            <!-- Suffix Field -->
            <div class="input-box">
                <label for="suffix">Suffix:</label>
                <select 
                    class="input-field" 
                    id="suffix" 
                    name="suffix">
                    <option value="None" {{ Auth::guard('student')->user()->Suffix == 'None' ? 'selected' : '' }}>None</option>
                    <option value="Jr." {{ Auth::guard('student')->user()->Suffix == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                    <option value="Sr." {{ Auth::guard('student')->user()->Suffix == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                    <option value="II" {{ Auth::guard('student')->user()->Suffix == 'II' ? 'selected' : '' }}>II</option>
                    <option value="III" {{ Auth::guard('student')->user()->Suffix == 'III' ? 'selected' : '' }}>III</option>
                    <option value="IV" {{ Auth::guard('student')->user()->Suffix == 'IV' ? 'selected' : '' }}>IV</option>
                    <option value="V" {{ Auth::guard('student')->user()->Suffix == 'V' ? 'selected' : '' }}>V</option>
                </select>
                <i class="bx bx-chevron-down"></i>
            </div>
        </div>

        <!-- Gender (Unchangeable) and Program -->
        <div class="two-forms">
        <div class="input-box">
                <label for="gender">Gender:</label>
                <input 
                    type="text" 
                    class="input-field" 
                    id="gender" 
                    name="gender" 
                    value="{{ 
                        strtolower(Auth::guard('student')->user()->Gender) === 'male' ? 'Male' : 
                        (strtolower(Auth::guard('student')->user()->Gender) === 'female' ? 'Female' : 
                        (strtolower(Auth::guard('student')->user()->Gender) === 'other' ? 'Other' : 'Prefer not to say')) 
                    }}" 
                    readonly>
                <i class="bx bx-chevron-down"></i>
            </div>

            <div class="input-box">
                <label for="program">College Program:</label>
                <select 
                    class="input-field" 
                    id="program" 
                    name="program_id">
                    <option value="1" {{ Auth::guard('student')->user()->Program_ID == 1 ? 'selected' : '' }}>Information Technology</option>
                    <option value="2" {{ Auth::guard('student')->user()->Program_ID == 2 ? 'selected' : '' }}>Home Economics</option>
                    <option value="3" {{ Auth::guard('student')->user()->Program_ID == 3 ? 'selected' : '' }}>Information Communication and Technology</option>
                    <option value="4" {{ Auth::guard('student')->user()->Program_ID == 4 ? 'selected' : '' }}>Human Resource Management</option>
                    <option value="5" {{ Auth::guard('student')->user()->Program_ID == 5 ? 'selected' : '' }}>Marketing Management</option>
                    <option value="6" {{ Auth::guard('student')->user()->Program_ID == 6 ? 'selected' : '' }}>Entrepreneurship</option>
                    <option value="7" {{ Auth::guard('student')->user()->Program_ID == 7 ? 'selected' : '' }}>Fiscal Administration</option>
                    <option value="8" {{ Auth::guard('student')->user()->Program_ID == 8 ? 'selected' : '' }}>Office Management Technology</option>
                </select>
                <i class="bx bx-chevron-down"></i>
            </div>
        </div>

        <!-- Contact Number and Email -->
        <div class="input-box">
            <label for="contact">Contact Number:</label>
            <input 
                type="text" 
                class="input-field" 
                id="contact" 
                name="contact_number" 
                value="{{ Auth::guard('student')->user()->ContactNumber }}" 
                maxlength="11" 
                placeholder="Enter new contact number">
            <i class="bx bx-contact"></i>
        </div>
        <div class="input-box">
            <label for="email">E-mail Address:</label>
            <input 
                type="email" 
                class="input-field" 
                id="email" 
                name="email" 
                value="{{ Auth::guard('student')->user()->Email }}" 
                placeholder="Enter new email">
            <i class="bx bx-envelope"></i>
        </div>

        <!-- Buttons -->
        <div class="button-group">
            <a href="{{ route('student.dashboard') }}" class="btn btn-cancel">Cancel</a>
            <button type="submit" class="btn btn-save">Save Changes</button>
        </div>
    </form>

</body>
</html>
