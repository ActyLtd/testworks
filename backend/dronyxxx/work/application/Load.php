<?php

/**
 * PHP version >= 7.4
 *
 * @author  Andrei Nikonorov <andrei@agencypin.com>
 * @link    https://dronyxxx@bitbucket.org/dronyxxx/org.git
 *
 * Main class to set global variables
 * This class contains general methods but methods should be refactored to controllers and/or additional classes
 */

class Load
{
    protected array $config;
    protected mysqli $db_connection;

    /**
     * Load config file
     */
    public function __construct()
    {
        include_once __DIR__ . '/config/config.php';
        $this->setConfig(CONFIG);
    }

    /**
     * Set MySQL connection and assign to class variable
     * Public access to class variable in current application
     */
    public function setMysqlConnection()
    {
        $mysqli = new mysqli($this->config['database']['host'], $this->config['database']['username'], $this->config['database']['password'], $this->config['database']['dbname']);
        $this->setDbConnection($mysqli);
    }

    /**
     * @param string $view_name
     * @return false|string
     */
    public function getView(string $view_name)
    {
        ob_start();
        include $this->config['viewsDir'].$view_name.".html";
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }

    /**
     * Global function to send CURL request to API
     * @param string $url
     * @param string $json_data
     * @return array
     */
    public function sendApiRequest(string $url, string $json_data): array
    {
        $ch = curl_init();
        try {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);
            curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, ['data' => $json_data]);
            $body = curl_exec($ch);
        } finally {
            if (!is_null($ch)) {
                curl_close($ch);
            }
        }
        return json_decode($body, true);
    }

    /**
     * Clear the database before new import.
     * It is not set in the current task how to handle old data imported previously
     * All related entities will be deleted once this is executed
     * TODO: would be good to catch exceptions here
     * TODO: create Class or Controller to handle this request
     */
    public function cleanDatabase()
    {
        $this->db_connection->query("DELETE FROM organization");
    }

    /**
     * @param array $data
     * @param int|null $parent_id
     * This recursive function will insert all organizations and their daughters to DB.
     * Would be right to return success message once executed without errors
     * TODO: create Class or Controller to handle this request
     */
    public function insertOrganizations(array $data, int $parent_id = null)
    {
        foreach ($data as $organization_row) {
            // check if organization exists in db
            $query = $this->db_connection->prepare('SELECT id FROM organization WHERE name = ?');
            $query->bind_param('s', $organization_row['org_name']);
            $query->execute();
            $result = $query->get_result();
            $row = $result->fetch_assoc();

            $organization_id = null;
            if (isset($row['id'])){
                $organization_id = $row['id'];
            }else{
                // add organization if org does not exist in database
                $query = $this->db_connection->prepare('INSERT INTO organization (name) VALUES (?)');
                $query->bind_param('s', $organization_row['org_name']);
                $query->execute();
                $organization_id = $this->db_connection->insert_id;
            }

            // add organization relations with last insert ID
            $query = $this->db_connection->prepare('INSERT INTO relation (organization_id, parent_id) VALUES (?, ?)');
            $query->bind_param('ii', $organization_id, $parent_id);
            $query->execute();

            if (isset($organization_row["daughters"])) {
                $this->insertOrganizations($organization_row["daughters"], $organization_id);
            }
        }
    }

    /**
     * Get all provided organization siblings (parents, sisters, daughters)
     * @param string $organization_name
     * @return array
     * TODO: create Class or Controller to handle this request
     */
    public function getOrganizationSiblings(string $organization_name): array
    {
        $response = [];

        // find provided organization ID in DB
        $query = $this->db_connection->prepare('SELECT id FROM organization WHERE name = ?');
        $query->bind_param('s', $organization_name);
        $query->execute();
        $result = $query->get_result();
        $row = $result->fetch_assoc();

        if (!isset($row['id'])){
            // TODO: throw exception
        }

        $parents_result = $this->db_connection->query('SELECT relation.parent_id, organization.name AS parent_name FROM relation LEFT JOIN organization ON organization.id = relation.parent_id WHERE relation.organization_id = '.$row['id']);
        while($parent_row = $parents_result->fetch_assoc()){
            $response[$parent_row['parent_id']] = [
                'relation_type' => 'parent',
                'org_name' => $parent_row['parent_name']
            ];

            $sisters_result = $this->db_connection->query('SELECT organization.id, organization.name FROM relation LEFT JOIN organization ON organization.id = relation.organization_id WHERE relation.parent_id = '.$parent_row['parent_id']);
            while($sister_row = $sisters_result->fetch_assoc()){
                $response[$sister_row['id']] = [
                    'relation_type' => 'sister',
                    'org_name' => $sister_row['name']
                ];
            }
            $sisters_result->close();

            $daughters_result = $this->db_connection->query('SELECT organization.id, organization.name FROM relation LEFT JOIN organization ON organization.id = relation.organization_id WHERE relation.parent_id = '.$row['id']);
            while($daughter_row = $daughters_result->fetch_assoc()){
                $response[$daughter_row['id']] = [
                    'relation_type' => 'daughter',
                    'org_name' => $daughter_row['name']
                ];
            }
            $daughters_result->close();
        }
        $parents_result->close();

        if (isset($response[$row['id']])) unset($response[$row['id']]); // removing organization that was searched for

        return $response;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @param array $config
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * @return mysqli
     */
    public function getDbConnection(): mysqli
    {
        return $this->db_connection;
    }

    /**
     * @param mysqli $db_connection
     */
    public function setDbConnection(mysqli $db_connection): void
    {
        $this->db_connection = $db_connection;
    }
}