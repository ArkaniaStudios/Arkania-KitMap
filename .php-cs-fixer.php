<?php
declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/build');

return (new PhpCsFixer\Config)
    ->setRiskyAllowed(true)
    ->setRules([
        'align_multiline_comment' => [
            'comment_type' => 'phpdocs_only'
        ],
        'array_indentation' => true,
        'array_syntax' => [
            "syntax" => "short"
        ],
        'binary_operator_spaces' => [
            'default' => 'single_space'
        ],
        'blank_line_after_namespace' => true,
        'blank_line_after_opening_tag' => true,
        'cast_spaces' => [
            'space' => 'single'
        ],
        'class_definition' => false,
        'class_reference_name_casing' => true,
        'compact_nullable_typehint' => true,
        'concat_space' => ['spacing' => 'one'],
        'elseif' => true,
        'empty_loop_body' => ['style' => 'braces'],
        'encoding' => true,
        'full_opening_tag' => true,
        'fully_qualified_strict_types' => false,
        'function_declaration' => true,
        'global_namespace_import' => [
            'import_constants' => false,
            'import_functions' => false,
            'import_classes' => null,
        ],
        'indentation_type' => true,
        'line_ending' => true,
        'logical_operators' => true,
        'lowercase_cast' => true,
        'lowercase_keywords' => true,
        'lowercase_static_reference' => true,
        'magic_constant_casing' => true,
        'magic_method_casing' => true,
        'method_argument_space' => ['after_heredoc' => true],
        'method_chaining_indentation' => true,
        'native_function_casing' => true,
        'native_function_type_declaration_casing' => true,
        'new_with_braces' => true,
        'no_blank_lines_after_phpdoc' => true,
        'no_closing_tag' => true,
        'no_empty_comment' => true,
        'no_empty_phpdoc' => true,
        'no_empty_statement' => true,
        'no_extra_blank_lines' => true,
        'no_leading_namespace_whitespace' => true,
        'no_multiline_whitespace_around_double_arrow' => true,
        'no_null_property_initialization' => true,
        'no_singleline_whitespace_before_semicolons' => true,
        'no_space_around_double_colon' => true,
        'no_spaces_after_function_name' => true,
        'no_spaces_around_offset' => true,
        'no_spaces_inside_parenthesis' => true,
        'no_superfluous_elseif' => true,
        'no_superfluous_phpdoc_tags' => ['allow_mixed' => true, 'remove_inheritdoc' => true],
        'no_trailing_whitespace' => true,
        'no_trailing_whitespace_in_comment' => true,
        'no_unused_imports' => true,
        'no_unneeded_control_parentheses' => true,
        'no_unneeded_curly_braces' => true,
        'no_useless_return' => true,
        'no_whitespace_before_comma_in_array' => true,
        'no_whitespace_in_blank_line' => true,
        'normalize_index_brace' => true,
        'nullable_type_declaration_for_default_null_value' => true,
        'object_operator_without_whitespace' => true,
        'ordered_imports' => true,
        'phpdoc_align' => ['align' => 'left'],
        'phpdoc_indent' => true,
        'phpdoc_line_span' => ['const' => 'single', 'property' => 'single'],
        'phpdoc_no_empty_return' => true,
        'phpdoc_order' => true,
        'phpdoc_trim' => true,
        'phpdoc_trim_consecutive_blank_line_separation' => true,
        'phpdoc_types' => true,
        'return_type_declaration' => ['space_before' => 'one'],
        'semicolon_after_instruction' => true,
        'single_blank_line_at_eof' => true,
        'single_class_element_per_statement' => true,
        'single_import_per_statement' => true,
        'single_line_throw' => true,
        'space_after_semicolon' => true,
        'switch_case_semicolon_to_colon' => true,
        'switch_case_space' => true,
        'header_comment' => [
            'comment_type' => 'comment',
            'header' => <<<BODY

    _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __  
   / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /  
  / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /  
 / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \  
/_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\

Arkania is a Minecraft Bedrock server created in 2019,
we mainly use PocketMine-MP to create content for our server
but we use something else like WaterDog PE

@author Arkania-Team
@link https://arkaniastudios.com

BODY,
            'location' => 'after_open'
        ]
    ])
    ->setFinder($finder)
    ->setIndent("\t")
    ->setLineEnding("\n");
