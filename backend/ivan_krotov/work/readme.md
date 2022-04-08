<h2>Used</h2>
<ul>
    <li>Docker</li>
    <li>Docker compose</li>
    <li>Composer</li>
    <li>PHP 7.4</li>
    <li>MySql 8</li>
</ul>

<h2>How to</h2>
<ul>
    <li><code>docker-compose up</code></li>
    <li>
        To add organizations: make post request to <a>localhost:8080/api/organization/add</a> with post object.
        Name post object <code>organizations</code>
    </li>
    <li>
        To get info about organization: make get request to <a>http://localhost:8080/api/Organization</a> with get params.
        Get params <code>id</code> or <code>name</code>
        example <a>http://localhost:8080/api/Organization?id=1</a> or <a>http://localhost:8080/api/Organization?name=banana</a>
    </li>
    <li>
        To get organization relations: make get request to <a>http://localhost:8080/api/Organization/relations</a> with get params.
        Get params <code>id</code> or <code>name</code>
        example <a>http://localhost:8080/api/Organization/relations?id=1</a> or <a>http://localhost:8080/api/Organization/relations?name=banana</a>
    </li>
</ul>

<h2>Without docker</h2>
<ul>
    <li>Create database by script in <code>images/mysql/init-script.sql</code></li>
    <li>Setup connection to database in <code>www/src/Base/Database.php</code></li>
</ul>