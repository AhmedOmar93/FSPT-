<nav>
    <ul>
        <li> <a href="{{ URL::route('home') }}">Home</a> </li>
        @if(Auth::check())
            <li> <a href="{{ URL::route('account-sign-out') }}">Sign Out</a> </li>
            <li> <a href="{{ URL::route('account-change-password') }}">Change Password</a> </li>
            <li> <a href="technicalSupport">Technical Support</a> </li>

            <li> <a href="{{ URL::route('group-create') }}">Create Group</a> </li>
            <li> <a href="{{ URL::route('Announcement-announcement') }}">Announcements</a> </li>
            <li> <a href="{{ URL::route('Announcement-home-announcement') }}">Home Announcements</a> </li>
            
            @else
            <li> <a href="{{ URL::route('account-sign-in') }}">Sign In</a> </li>
            <li> <a href="{{ URL::route('account-create') }}">Create Account</a> </li>
            <li> <a href="{{ URL::route('account-forgot-password') }}">Forget Password</a> </li>


            @endif


    </ul>
</nav>