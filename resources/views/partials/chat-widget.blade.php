@php
    $showChat = false;
    
    // Check if user is not logged in (guest) or has resident role
    if (!auth()->check() || (auth()->check() && auth()->user()->role === 'resident')) {
        $showChat = true;
    }
@endphp

@if($showChat)
<!--Start of Tawk.to Script with Enhanced User Information-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();

// Pre-define visitor information if user is logged in
@auth
    Tawk_API.onLoad = function(){
        Tawk_API.setAttributes({
            'name'  : '{{ auth()->user()->name }}',
            'email' : '{{ auth()->user()->email }}',
            'id'    : '{{ auth()->user()->id }}',
            'userRole' : '{{ auth()->user()->role }}',
            'area' : '{{ auth()->user()->area ? auth()->user()->area->name : "Not specified" }}'
        }, function(error){});
    };
@endauth

// Standard Tawk.to initialization
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/68b67e41dd3a541924dc5576/1j44dqcac';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
@endif