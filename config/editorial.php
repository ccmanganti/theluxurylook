<?php

// Copy direction shared by the website and the visual-only preview.
// Editors can replace all project imagery through Filament without rewriting templates.
return json_decode(file_get_contents(database_path('data/editorial-content.json')), true, 512, JSON_THROW_ON_ERROR);
