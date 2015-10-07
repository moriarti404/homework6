<?php
function create_vacancies() {
    register_post_type( 'areas_of_work',
        array(
            'labels' => array(
                'name' => 'Vacancies',
                'singular_name' => 'Vacancy',
                'add_new' => 'Add new',
                'add_new_item' => 'Add new areas_of_work',
                'edit' => 'Edit',
                'edit_item' => 'Edit areas_of_work',
                'new_item' => 'New vacancy',
                'view' => 'View',
                'view_item' => 'View vacancy',
                'search_items' => 'Search areas_of_work',
                'not_found' => 'No areas_of_work found',
                'not_found_in_trash' => 'No areas_of_work found in trash',
                'parent' => 'Parent areas_of_work'
            ),
            'description' => 'Our areas_of_work',
            'public' => true,
            'menu_position' => 3,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-clipboard',
            'has_archive' => true
        )
    );
}

function create_worker() {
    register_post_type( 'workers',
        array(
            'labels' => array(
                'name' => 'Workers',
                'singular_name' => 'Worker',
                'add_new' => 'Add New',
                'add_new_item' => 'Add new workers',
                'edit' => 'Edit',
                'edit_item' => 'Edit workers',
                'new_item' => 'New workers',
                'view' => 'View',
                'view_item' => 'View workers',
                'search_items' => 'Search workers',
                'not_found' => 'No workers found',
                'not_found_in_trash' => 'No workers found in trash',
                'parent' => 'Parent workers'
            ),
            'description' => 'Our workers',
            'public' => true,
            'menu_position' => 4,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-admin-users',
            'has_archive' => true
        )
    );
}

function create_areas_of_work() {
    register_post_type( 'areas_of_work',
        array(
            'labels' => array(
                'name' => 'Areas of work',
                'singular_name' => 'Areas of work',
                'add_new' => 'Add new',
                'add_new_item' => 'Add new area of work',
                'edit' => 'Edit',
                'edit_item' => 'Edit area of work',
                'new_item' => 'New area of work',
                'view' => 'View',
                'view_item' => 'View area of work',
                'search_items' => 'Search areas of work',
                'not_found' => 'No areas of work found',
                'not_found_in_trash' => 'No areas of work found in Trash',
                'parent' => 'Parent areas of work'
            ),
            'description' => 'Our areas of work',
            'public' => true,
            'menu_position' => 5,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
            'taxonomies' => array( '' ),
            'menu_icon' => 'dashicons-screenoptions',
            'has_archive' => true
        )
    );
}


function my_widgets_init() {
    register_sidebar( array(
        'name' => 'sidebar',
        'id' => 'sidebar',
        'description' => __( 'My Sidebar'),
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="rounded">',
        'after_title' => '</h2>',
    ) );
}
add_action( 'widgets_init', 'my_widgets_init' );


function my_salary() {
    add_meta_box(
        'my_salary', // Идентификатор(id)
        'My Meta Box', // Заголовок области с мета-полями(title)
        'show_my_salary', // Вызов(callback)
        'areas_of_work', // Где будет отображаться наше поле
        'normal',
        'high');
}
add_action('add_meta_boxes', 'my_salary'); // Запускаем функцию

$salary = array(
    array(
        'label' => 'Salary',
        'desc'  => 'Description',
        'id'    => 'mytextinput', // даем идентификатор.
        'type'  => 'text'  // Указываем тип поля.
    )

);

// Вызов метаполей  
function show_my_salary() {
    global $salary; // Обозначим наш массив с полями глобальным
    global $post;  // Глобальный $post для получения id создаваемого/редактируемого поста
// Выводим скрытый input, для верификации. Безопасность прежде всего!
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Начинаем выводить таблицу с полями через цикл
    echo '<table class="form-table">';
    foreach ($salary as $field) {
        // Получаем значение если оно есть для этого поля 
        $meta = get_post_meta($post->ID, $field['id'], true);
        // Начинаем выводить таблицу
        echo '<tr> 
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
                <td>';
        switch($field['type']) {
            case 'text':
                echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
        <br /><span class="description">'.$field['desc'].'</span>';
                break;
            case 'textarea':
                echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
        <br /><span class="description">'.$field['desc'].'</span>';
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
        <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                break;
// Всплывающий список  
            case 'select':
                echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                }
                echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                break;
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

// Пишем функцию для сохранения
function save_my_salary($post_id) {
    global $salary;  // Массив с нашими полями

    // проверяем наш проверочный код 
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // Проверяем авто-сохранение 
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // Проверяем права доступа  
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Если все отлично, прогоняем массив через foreach
    foreach ($salary as $field) {
        $old = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {  // Если данные новые
            update_post_meta($post_id, $field['id'], $new); // Обновляем данные
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old); // Если данных нету, удаляем мету.
        }
    } // end foreach  
}
add_action('save_post', 'save_my_salary'); // Запускаем функцию сохранения


/*Второе кастомное поле*/
/*Второе кастомное поле*/
/*Второе кастомное поле*/
/*Второе кастомное поле*/
function my_position() {
    add_meta_box(
        'my_position', // Идентификатор(id)
        'Position', // Заголовок области с мета-полями(title)
        'show_my_position', // Вызов(callback)
        'workers', // Где будет отображаться наше поле, в нашем случае в Записях
        'normal',
        'high');
}
add_action('add_meta_boxes', 'my_position'); // Запускаем функцию

$position = array(
    array(
        'label' => 'Position',
        'desc'  => 'Describe',
        'id'    => 'mytextinput2', // даем идентификатор.
        'type'  => 'text'  // Указываем тип поля.
    )
);

// Вызов метаполей  
function show_my_position() {
    global $position; // Обозначим наш массив с полями глобальным
    global $post;  // Глобальный $post для получения id создаваемого/редактируемого поста
// Выводим скрытый input, для верификации. Безопасность прежде всего!
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Начинаем выводить таблицу с полями через цикл
    echo '<table class="form-table">';
    foreach ($position as $field) {
        // Получаем значение если оно есть для этого поля 
        $meta = get_post_meta($post->ID, $field['id'], true);
        // Начинаем выводить таблицу
        echo '<tr> 
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th> 
                <td>';
        switch($field['type']) {
            case 'text':
                echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
        <br /><span class="description">'.$field['desc'].'</span>';
                break;
            case 'textarea':
                echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
        <br /><span class="description">'.$field['desc'].'</span>';
                break;
            case 'checkbox':
                echo '<input type="checkbox" name="'.$field['id'].'" id="'.$field['id'].'" ',$meta ? ' checked="checked"' : '','/>
        <label for="'.$field['id'].'">'.$field['desc'].'</label>';
                break;
// Всплывающий список  
            case 'select':
                echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                foreach ($field['options'] as $option) {
                    echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                }
                echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                break;
        }
        echo '</td></tr>';
    }
    echo '</table>';
}

// Пишем функцию для сохранения
function save_my_position($post_id) {
    global $position;  // Массив с нашими полями

    // проверяем наш проверочный код 
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // Проверяем авто-сохранение 
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // Проверяем права доступа  
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
    } elseif (!current_user_can('edit_post', $post_id)) {
        return $post_id;
    }

    // Если все отлично, прогоняем массив через foreach
    foreach ($position as $field) {
        $old = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {  // Если данные новые
            update_post_meta($post_id, $field['id'], $new); // Обновляем данные
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old); // Если данных нету, удаляем мету.
        }
    } // end foreach  
}
add_action('save_post', 'save_my_position'); // Запускаем функцию сохранения
?>


