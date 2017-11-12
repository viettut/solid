<?php
/**
 * Created by PhpStorm.
 * User: giang
 * Date: 11/12/17
 * Time: 10:02 PM
 */

namespace App;


class SQLiteQuery
{
    /**
     * PDO object
     * @var \PDO
     */
    private $pdo;

    /**
     * Initialize the object with a specified PDO object
     * @param \PDO $pdo
     */
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get all projects
     * @return type
     */
    public function getProjects() {
        $stmt = $this->pdo->query('SELECT project_id, project_name '
            . 'FROM projects');
        $projects = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $projects[] = [
                'project_id' => $row['project_id'],
                'project_name' => $row['project_name']
            ];
        }
        return $projects;
    }

    /**
     * Get the project as an object list
     * @return an array of Project objects
     */
    public function getProjectObjectList() {
        $stmt = $this->pdo->query('SELECT project_id, project_name '
            . 'FROM projects');

        $projects = [];
        while ($project = $stmt->fetchObject()) {
            $projects[] = $project;
        }

        return $projects;
    }

    /**
     * Get the number of tasks in a project
     * @param int $projectId
     * @return int
     */
    public function getTaskCountByProject($projectId) {

        $stmt = $this->pdo->prepare('SELECT COUNT(*) 
                                    FROM tasks
                                   WHERE project_id = :project_id;');
        $stmt->bindParam(':project_id', $projectId);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Get tasks by the project id
     * @param int $projectId
     * @return an array of tasks in a specified project
     */
    public function getTaskByProject($projectId) {
        // prepare SELECT statement
        $stmt = $this->pdo->prepare('SELECT task_id,
                                            task_name,
                                            start_date,
                                            completed_date,
                                            completed,
                                            project_id
                                       FROM tasks
                                      WHERE project_id = :project_id;');

        $stmt->execute([':project_id' => $projectId]);

        // for storing tasks
        $tasks = [];

        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tasks[] = [
                'task_id' => $row['task_id'],
                'task_name' => $row['task_name'],
                'start_date' => $row['start_date'],
                'completed_date' => $row['completed_date'],
                'completed' => $row['completed'],
                'project_id' => $row['project_id'],
            ];
        }

        return $tasks;
    }
}