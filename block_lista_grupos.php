<?php

/**
 * Form for editing HTML block instances.
 *
 * @package   block_lista_grupos
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->libdir . '/accesslib.php');

class block_lista_grupos extends block_base {

    public function init() {
        $this->title = get_string('pluginname', 'block_lista_grupos');
    }

    public function get_content() {
        global $DB, $COURSE;

        // Verificar si ya se ha cargado el contenido
        if ($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass;
        $this->content->text = '';
        $this->content->footer = '';

        // Obtener los usuarios matriculados en el curso actual
        $context = context_course::instance($COURSE->id);
        $students = get_enrolled_users($context, 'mod/assign:submit');

        // Contador de estudiantes
        $student_count = 0;

        $this->content->text .= '<ul>';

        foreach ($students as $student) {
            $roles = get_user_roles($context, $student->id);
            foreach ($roles as $role) {
                if ($role->shortname === 'student') {
                    $student_count++;
                    $this->content->text .= '<li>' . fullname($student) . ' - ' . $student->email . '</li>';
                }
            }
        }

        $this->content->text .= '</ul>';
        $this->content->text = '<h4>Total de estudiantes: ' . $student_count . '</h4>' . $this->content->text;

        return $this->content;
    }

    public function applicable_formats() {
        return ['course-view' => true];
    }
}

?>
