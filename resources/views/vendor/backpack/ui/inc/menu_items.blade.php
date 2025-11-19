{{-- This file is used for menu items by any Backpack v6 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Availabilities" icon="la la-question" :link="backpack_url('availability')" />
<x-backpack::menu-item title="Profiles" icon="la la-question" :link="backpack_url('profile')" />
<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="Messages" icon="la la-question" :link="backpack_url('message')" />
<x-backpack::menu-item title="Online users" icon="la la-question" :link="backpack_url('online-user')" />
<x-backpack::menu-item title="User positions" icon="la la-question" :link="backpack_url('user-position')" />
<x-backpack::menu-item title="User preferences" icon="la la-question" :link="backpack_url('user-preference')" />