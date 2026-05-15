<?php
if (is_post_type_archive('hitboox_project') || is_tax('hitboox_project_platform') || is_tax('hitboox_project_genre')) {
    get_template_part('template-parts/archive-project');
}else{
    get_template_part('template-parts/archive-post');
}
