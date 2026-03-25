<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Profile - {{ $user->student_name }}</title>

<style>
    body {
        margin: 0;
        font-family: "Segoe UI", Arial, sans-serif;
        background: #f5f5f5;
    }

    .container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 16px;
    }

    /* Card */
    .profile-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    /* Header */
    .profile-header {
        background: linear-gradient(90deg, #f9a825, #ffb300);
        padding: 30px 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .profile-image {
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 42px;
        font-weight: bold;
        color: #f9a825;
        border: 4px solid #fff;
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
    }

    .profile-info h1 {
        margin: 0;
        font-size: 28px;
        color: #2e2e2e;
    }

    .profile-info p {
        margin: 4px 0 10px;
        color: #333;
        font-weight: 500;
    }

    .login-btn {
        display: inline-block;
        padding: 10px 18px;
        background: #2e2e2e;
        color: #fff;
        text-decoration: none;
        border-radius: 6px;
        font-size: 14px;
    }

    .login-btn:hover {
        background: #000;
    }

    /* Body */
    .profile-body {
        padding: 30px;
    }

    .section-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
    }

    .info-section {
        background: #fafafa;
        border: 1px solid #eee;
        border-radius: 8px;
        padding: 20px;
    }

    .info-section h3 {
        margin: 0 0 16px;
        font-size: 16px;
        color: #f9a825;
        border-bottom: 2px solid #f9a825;
        padding-bottom: 6px;
    }

    .info-item {
        margin-bottom: 12px;
    }

    .info-label {
        font-size: 12px;
        color: #777;
        text-transform: uppercase;
    }

    .info-value {
        font-size: 15px;
        font-weight: 500;
        color: #333;
    }

    .highlight {
        background: #fff8e1;
        padding: 8px;
        border-radius: 5px;
        font-family: monospace;
    }

    .note {
        margin-top: 12px;
        background: #fff3cd;
        border-left: 4px solid #f9a825;
        padding: 10px;
        font-size: 13px;
        color: #6b5500;
    }

    @media (max-width: 768px) {
        .profile-header {
            text-align: center;
            justify-content: center;
        }
    }
</style>
</head>

<body>

<div class="container">
    <div class="profile-card">

        <!-- HEADER -->
        <div class="profile-header">
            <div class="profile-image">
                @if(isset($user->image) && $user->image)
                    <img src="{{ $user->image }}" alt="{{ $user->student_name }}">
                @else
                    @php
                        $nameParts = explode(' ', trim($user->student_name));
                        $initials = '';
                        foreach($nameParts as $part) {
                            if(!empty($part)) {
                                $initials .= strtoupper(substr($part, 0, 1));
                            }
                        }
                        echo substr($initials, 0, 2); // Show max 2 initials
                    @endphp
                @endif
            </div>

            <div class="profile-info">
                <h1>{{ $user->student_name }}</h1>
                <p>Class {{ $user->class }} Student</p>
                <a href="https://app.nrsm.in/open" target="_blank" class="login-btn">
                    Login to Student App
                </a>
            </div>
        </div>

        <!-- BODY -->
        <div class="profile-body">
            <div class="section-grid">

                <!-- Login -->
                <div class="info-section">
                    <h3>Login Credentials</h3>
                    <div class="info-item">
                        <div class="info-label">Username</div>
                        <div class="info-value">
                            {{ !empty($user->student_phone) ? $user->student_phone : $user->username }}
                        </div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">Password</div>
                        <div class="info-value highlight">
                            {{ strtoupper(str_replace(' ', '', $user->password_name)) }}123@
                        </div>
                    </div>

                    <div class="note">
                        Login at <strong>app.nrsm.in/open</strong>
                    </div>
                </div>

                <!-- Contact -->
                <div class="info-section">
                    <h3>Contact Information</h3>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $user->student_email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $user->student_phone }}</div>
                    </div>
                </div>

                <!-- Parent -->
                <div class="info-section">
                    <h3>Parent Information</h3>
                    <div class="info-item">
                        <div class="info-label">Parent Name</div>
                        <div class="info-value">{{ $user->parent_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Parent Phone</div>
                        <div class="info-value">{{ $user->parent_phone ?? 'Not provided' }}</div>
                    </div>
                </div>

                <!-- School -->
                <div class="info-section">
                    <h3>School Information</h3>
                    <div class="info-item">
                        <div class="info-label">School Name</div>
                        <div class="info-value">{{ $user->school_name ?? 'Not available' }}</div>
                    </div>
                    @if($user->school_city)
                    <div class="info-item">
                        <div class="info-label">City</div>
                        <div class="info-value">{{ $user->school_city }}</div>
                    </div>
                    @endif
                    @if($user->school_state)
                    <div class="info-item">
                        <div class="info-label">State</div>
                        <div class="info-value">{{ $user->school_state }}</div>
                    </div>
                    @endif
                </div>

                <!-- Activity -->
                <div class="info-section">
                    <h3>Activity</h3>
                    <div class="info-item">
                        <div class="info-label">Created At</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Last Updated</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($user->updated_at)->format('M d, Y h:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Olympiad Registration -->
                <div class="info-section">
                    <h3>Olympiad Registration</h3>
                    @if($user->registered_for_olympiad == 1)
                        <div class="info-item">
                            <div class="info-value" style="color: #4caf50; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                <span style="font-size: 20px;">✓</span>
                                You are registered for Olympiad
                            </div>
                        </div>
                    @else
                        <div class="info-item">
                            <div class="info-value" style="margin-bottom: 12px;">
                                You are not registered yet
                            </div>
                            <a href="https://app.nrsm.in/open" target="_blank" class="login-btn" style="display: inline-block; padding: 10px 18px; background: #f9a825; color: #fff; text-decoration: none; border-radius: 6px; font-size: 14px;">
                                Login to Register
                            </a>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Update Profile Notice -->
            <div style="margin-top: 30px; padding: 20px; background: #fff3cd; border-left: 4px solid #f9a825; border-radius: 8px;">
                <p style="margin: 0 0 10px; color: #6b5500; font-size: 14px;">
                    <strong>📝 Need to update your information?</strong>
                </p>
                <p style="margin: 0 0 12px; color: #6b5500; font-size: 13px;">
                    All information displayed here can be edited after logging in to the app.
                </p>
                <a href="https://app.nrsm.in/open" target="_blank" class="login-btn" style="background: #f9a825; display: inline-block;">
                    Change or Update Your Profile
                </a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
