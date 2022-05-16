<div class="container">
    <h1>Doctors</h1>
        <p>{success}</p>
    <div id="medicos">
        {medicos}
            <ul class="medico">
                <li>Name: {name}</li>
                <li>Specialty: {specialty}</li>
                {if isLoggedIn}
                    <li>Nib: {nib}</li>
                    <li>Nif: {nif}</li>
                    <li>Address: {address}</li>
                    <li>Birthdate: {birthday}</li>
                    {if hasAdmin}
                        <li>
                            <a href="{edit_url}{id}">Edit</a>
                            ||
                            <a href="{remove_url}{id}">Remove</a>
                            ||
                            <a href="{seemore_url}{id}">See More</a>
                        </li>
                    {/if}
                {/if}
            </ul>
            <hr>
        {/medicos}
    </div>
</div>