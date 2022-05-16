<nav>
    <div>
        <span><a href="<?php echo base_url(); ?>">Home</a> | </span>
        <span><a href="<?php echo base_url('doctors/'); ?>">Doctors</a> | </span>
        <span><a href="<?php echo base_url('nurses/'); ?>">Nurses</a> | </span>
        <span><a href="<?php echo base_url('patients/'); ?>">Patients</a> | </span>
        <?php if (!isset($isLoggedIn) || !$isLoggedIn): ?>
            <span><a href="<?php echo base_url('register/'); ?>">Register</a> | </span>
            <span><a href="<?php echo base_url('login/'); ?>">Login</a> | </span>
        <?php else: ?>
            <span><a href="<?php echo base_url('consultations/'); ?>">Consultations</a> | </span>
            <span><a href="<?php echo base_url('logout'); ?>">Logout</a> | </span>
        <?php endif; ?>
        <span><a href="<?php echo base_url('contactus'); ?>">Contact Us</a></span>
    </div>
</nav>
