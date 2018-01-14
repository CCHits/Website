{include file="partials/header.html.tpl"}
<div class="container" id="chart">
    <div class="row row-header">
        <div class="col">
            <header>Applications</header>
        </div>
    </div>
    <div class="row" id="main">
        <div class="col-3">
            <div class="list-group">
                <a class="list-group-item active" href="{$baseURL}developer/applications">Applications</a>
                <a class="list-group-item" href="{$baseURL}developer/account">Account</a>
                <a class="list-group-item" href="{$baseURL}developer/logout">Logout</a>
            </div>
        </div>
        <div class="col-9">
            {if isset($message)}
            <div class="alert alert-primary" role="alert">
                {$message}
            </div>
            {/if}
            {if isset($alert)}
            <div class="alert alert-danger" role="alert">
                {$alert}
            </div>
            {/if}
            <p>
                <a href="{$baseURL}developer/new" class="btn btn-primary">Create new application</a>
            </p>
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Client ID</th>
                        <th>State</th>
                        <!--
                        <th></th>
                        -->
                    </tr>
                </thead>
                <tbody>
                    {foreach from=$applications item=application}
                    <tr>
                        <td>{$application.strApplicationName}</td>
                        <td>{$application.strApplicationClientID}</td>
                        <td>{$application.strApplicationState}</td>
                        <!--
                        <td><a href="#" class="btn btn-sm btn-primary">View</a>&nbsp;<a href="#" class="btn btn-sm btn-primary">Edit</a></td>
                        -->
                    </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
{include file="partials/footer.html.tpl"}