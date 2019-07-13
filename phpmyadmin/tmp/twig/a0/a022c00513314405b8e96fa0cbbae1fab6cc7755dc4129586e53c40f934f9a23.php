<?php

/* columns_definitions/column_attributes.twig */
class __TwigTemplate_bd04be537d6f7b257537d6e39fb91252436f0be1eabf7bed010f8e95a7c85ac8 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 2
        $context["ci"] = 0;
        // line 3
        echo "
";
        // line 6
        $context["ci_offset"] =  -1;
        // line 7
        echo "
<td class=\"center\">
    ";
        // line 10
        echo "    ";
        $this->loadTemplate("columns_definitions/column_name.twig", "columns_definitions/column_attributes.twig", 10)->display(array("column_number" =>         // line 11
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 12
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 13
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 14
(isset($context["column_meta"]) ? $context["column_meta"] : null), "cfg_relation" =>         // line 15
(isset($context["cfg_relation"]) ? $context["cfg_relation"] : null), "max_rows" =>         // line 16
(isset($context["max_rows"]) ? $context["max_rows"] : null)));
        // line 18
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 19
        echo "</td>
<td class=\"center\">
    ";
        // line 22
        echo "    ";
        $this->loadTemplate("columns_definitions/column_type.twig", "columns_definitions/column_attributes.twig", 22)->display(array("column_number" =>         // line 23
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 24
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 25
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 26
(isset($context["column_meta"]) ? $context["column_meta"] : null), "type_upper" =>         // line 27
(isset($context["type_upper"]) ? $context["type_upper"] : null)));
        // line 29
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 30
        echo "</td>
<td class=\"center\">
    ";
        // line 33
        echo "    ";
        $this->loadTemplate("columns_definitions/column_length.twig", "columns_definitions/column_attributes.twig", 33)->display(array("column_number" =>         // line 34
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 35
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 36
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "length_values_input_size" =>         // line 37
(isset($context["length_values_input_size"]) ? $context["length_values_input_size"] : null), "length_to_display" =>         // line 38
(isset($context["length"]) ? $context["length"] : null)));
        // line 40
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 41
        echo "</td>
<td class=\"center\">
    ";
        // line 44
        echo "    ";
        $this->loadTemplate("columns_definitions/column_default.twig", "columns_definitions/column_attributes.twig", 44)->display(array("column_number" =>         // line 45
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 46
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 47
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 48
(isset($context["column_meta"]) ? $context["column_meta"] : null), "type_upper" =>         // line 49
(isset($context["type_upper"]) ? $context["type_upper"] : null), "char_editing" =>         // line 50
(isset($context["char_editing"]) ? $context["char_editing"] : null)));
        // line 52
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 53
        echo "</td>
<td class=\"center\">
    ";
        // line 56
        echo "    ";
        echo PhpMyAdmin\Charsets::getCollationDropdownBox(        // line 57
(isset($context["dbi"]) ? $context["dbi"] : null),         // line 58
(isset($context["disable_is"]) ? $context["disable_is"] : null), (("field_collation[" .         // line 59
(isset($context["column_number"]) ? $context["column_number"] : null)) . "]"), ((("field_" .         // line 60
(isset($context["column_number"]) ? $context["column_number"] : null)) . "_") . ((isset($context["ci"]) ? $context["ci"] : null) - (isset($context["ci_offset"]) ? $context["ci_offset"] : null))), (( !twig_test_empty($this->getAttribute(        // line 61
(isset($context["column_meta"]) ? $context["column_meta"] : null), "Collation", array(), "array"))) ? ($this->getAttribute((isset($context["column_meta"]) ? $context["column_meta"] : null), "Collation", array(), "array")) : (null)), false);
        // line 63
        echo "
    ";
        // line 64
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 65
        echo "</td>
<td class=\"center\">
    ";
        // line 68
        echo "    ";
        $this->loadTemplate("columns_definitions/column_attribute.twig", "columns_definitions/column_attributes.twig", 68)->display(array("column_number" =>         // line 69
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 70
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 71
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 72
(isset($context["column_meta"]) ? $context["column_meta"] : null), "extracted_columnspec" =>         // line 73
(isset($context["extracted_columnspec"]) ? $context["extracted_columnspec"] : null), "submit_attribute" =>         // line 74
(isset($context["submit_attribute"]) ? $context["submit_attribute"] : null), "attribute_types" =>         // line 75
(isset($context["attribute_types"]) ? $context["attribute_types"] : null)));
        // line 77
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 78
        echo "</td>
<td class=\"center\">
    ";
        // line 81
        echo "    ";
        $this->loadTemplate("columns_definitions/column_null.twig", "columns_definitions/column_attributes.twig", 81)->display(array("column_number" =>         // line 82
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 83
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 84
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 85
(isset($context["column_meta"]) ? $context["column_meta"] : null)));
        // line 87
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 88
        echo "</td>
";
        // line 89
        if ((array_key_exists("change_column", $context) &&  !twig_test_empty((isset($context["change_column"]) ? $context["change_column"] : null)))) {
            // line 90
            echo "    ";
            // line 91
            echo "    <td class=\"center\">
        ";
            // line 92
            $this->loadTemplate("columns_definitions/column_adjust_privileges.twig", "columns_definitions/column_attributes.twig", 92)->display(array("column_number" =>             // line 93
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 94
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 95
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "privs_available" =>             // line 96
(isset($context["privs_available"]) ? $context["privs_available"] : null)));
            // line 98
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 99
            echo "    </td>
";
        }
        // line 101
        if ( !(isset($context["is_backup"]) ? $context["is_backup"] : null)) {
            // line 102
            echo "    ";
            // line 103
            echo "    <td class=\"center\">
        ";
            // line 104
            $this->loadTemplate("columns_definitions/column_indexes.twig", "columns_definitions/column_attributes.twig", 104)->display(array("column_number" =>             // line 105
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 106
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 107
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 108
(isset($context["column_meta"]) ? $context["column_meta"] : null)));
            // line 110
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 111
            echo "    </td>
";
        }
        // line 113
        echo "<td class=\"center\">
    ";
        // line 115
        echo "    ";
        $this->loadTemplate("columns_definitions/column_auto_increment.twig", "columns_definitions/column_attributes.twig", 115)->display(array("column_number" =>         // line 116
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 117
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 118
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>         // line 119
(isset($context["column_meta"]) ? $context["column_meta"] : null)));
        // line 121
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 122
        echo "</td>
<td class=\"center\">
    ";
        // line 125
        echo "    ";
        $this->loadTemplate("columns_definitions/column_comment.twig", "columns_definitions/column_attributes.twig", 125)->display(array("column_number" =>         // line 126
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>         // line 127
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>         // line 128
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "max_length" =>         // line 129
(isset($context["max_length"]) ? $context["max_length"] : null), "value" => (((($this->getAttribute(        // line 130
(isset($context["column_meta"]) ? $context["column_meta"] : null), "Field", array(), "array", true, true) && twig_test_iterable(        // line 131
(isset($context["comments_map"]) ? $context["comments_map"] : null))) && $this->getAttribute(        // line 132
(isset($context["comments_map"]) ? $context["comments_map"] : null), $this->getAttribute((isset($context["column_meta"]) ? $context["column_meta"] : null), "Field", array(), "array"), array(), "array", true, true))) ? (twig_escape_filter($this->env, $this->getAttribute(        // line 133
(isset($context["comments_map"]) ? $context["comments_map"] : null), $this->getAttribute((isset($context["column_meta"]) ? $context["column_meta"] : null), "Field", array(), "array"), array(), "array"))) : (""))));
        // line 135
        echo "    ";
        $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
        // line 136
        echo "</td>
 ";
        // line 138
        if ((isset($context["is_virtual_columns_supported"]) ? $context["is_virtual_columns_supported"] : null)) {
            // line 139
            echo "    <td class=\"center\">
        ";
            // line 140
            $this->loadTemplate("columns_definitions/column_virtuality.twig", "columns_definitions/column_attributes.twig", 140)->display(array("column_number" =>             // line 141
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 142
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 143
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 144
(isset($context["column_meta"]) ? $context["column_meta"] : null), "char_editing" =>             // line 145
(isset($context["char_editing"]) ? $context["char_editing"] : null), "expression" => (($this->getAttribute(            // line 146
(isset($context["column_meta"]) ? $context["column_meta"] : null), "Expression", array(), "array", true, true)) ? ($this->getAttribute((isset($context["column_meta"]) ? $context["column_meta"] : null), "Expression", array(), "array")) : ("")), "options" =>             // line 147
(isset($context["options"]) ? $context["options"] : null)));
            // line 149
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 150
            echo "    </td>
";
        }
        // line 153
        if (array_key_exists("fields_meta", $context)) {
            // line 154
            echo "    ";
            $context["current_index"] = 0;
            // line 155
            echo "    ";
            $context["cols"] = (twig_length_filter($this->env, (isset($context["move_columns"]) ? $context["move_columns"] : null)) - 1);
            // line 156
            echo "    ";
            $context["break"] = false;
            // line 157
            echo "    ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(range(0, (isset($context["cols"]) ? $context["cols"] : null)));
            foreach ($context['_seq'] as $context["_key"] => $context["mi"]) {
                if ((($this->getAttribute($this->getAttribute((isset($context["move_columns"]) ? $context["move_columns"] : null), $context["mi"], array(), "array"), "name", array()) == $this->getAttribute((isset($context["column_meta"]) ? $context["column_meta"] : null), "Field", array(), "array")) &&  !(isset($context["break"]) ? $context["break"] : null))) {
                    // line 158
                    echo "        ";
                    $context["current_index"] = $context["mi"];
                    // line 159
                    echo "        ";
                    $context["break"] = true;
                    // line 160
                    echo "    ";
                }
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['mi'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 161
            echo "
    <td class=\"center\">
        ";
            // line 163
            $this->loadTemplate("columns_definitions/move_column.twig", "columns_definitions/column_attributes.twig", 163)->display(array("column_number" =>             // line 164
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 165
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 166
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 167
(isset($context["column_meta"]) ? $context["column_meta"] : null), "move_columns" =>             // line 168
(isset($context["move_columns"]) ? $context["move_columns"] : null), "current_index" =>             // line 169
(isset($context["current_index"]) ? $context["current_index"] : null)));
            // line 171
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 172
            echo "    </td>
";
        }
        // line 174
        echo "
";
        // line 175
        if ((($this->getAttribute((isset($context["cfg_relation"]) ? $context["cfg_relation"] : null), "mimework", array(), "array") && (isset($context["browse_mime"]) ? $context["browse_mime"] : null)) && $this->getAttribute((isset($context["cfg_relation"]) ? $context["cfg_relation"] : null), "commwork", array(), "array"))) {
            // line 176
            echo "    <td class=\"center\">
        ";
            // line 178
            echo "        ";
            $this->loadTemplate("columns_definitions/mime_type.twig", "columns_definitions/column_attributes.twig", 178)->display(array("column_number" =>             // line 179
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 180
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 181
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 182
(isset($context["column_meta"]) ? $context["column_meta"] : null), "available_mime" =>             // line 183
(isset($context["available_mime"]) ? $context["available_mime"] : null), "mime_map" =>             // line 184
(isset($context["mime_map"]) ? $context["mime_map"] : null)));
            // line 186
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 187
            echo "    </td>
    <td class=\"center\">
        ";
            // line 190
            echo "        ";
            $this->loadTemplate("columns_definitions/transformation.twig", "columns_definitions/column_attributes.twig", 190)->display(array("column_number" =>             // line 191
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 192
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 193
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 194
(isset($context["column_meta"]) ? $context["column_meta"] : null), "available_mime" =>             // line 195
(isset($context["available_mime"]) ? $context["available_mime"] : null), "mime_map" =>             // line 196
(isset($context["mime_map"]) ? $context["mime_map"] : null), "type" => "transformation"));
            // line 199
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 200
            echo "    </td>
    <td class=\"center\">
        ";
            // line 203
            echo "        ";
            $this->loadTemplate("columns_definitions/transformation_option.twig", "columns_definitions/column_attributes.twig", 203)->display(array("column_number" =>             // line 204
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 205
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 206
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 207
(isset($context["column_meta"]) ? $context["column_meta"] : null), "mime_map" =>             // line 208
(isset($context["mime_map"]) ? $context["mime_map"] : null), "type_prefix" => ""));
            // line 211
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 212
            echo "    </td>
    <td class=\"center\">
        ";
            // line 215
            echo "        ";
            $this->loadTemplate("columns_definitions/transformation.twig", "columns_definitions/column_attributes.twig", 215)->display(array("column_number" =>             // line 216
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 217
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 218
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 219
(isset($context["column_meta"]) ? $context["column_meta"] : null), "available_mime" =>             // line 220
(isset($context["available_mime"]) ? $context["available_mime"] : null), "mime_map" =>             // line 221
(isset($context["mime_map"]) ? $context["mime_map"] : null), "type" => "input_transformation"));
            // line 224
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 225
            echo "    </td>
    <td class=\"center\">
        ";
            // line 228
            echo "        ";
            $this->loadTemplate("columns_definitions/transformation_option.twig", "columns_definitions/column_attributes.twig", 228)->display(array("column_number" =>             // line 229
(isset($context["column_number"]) ? $context["column_number"] : null), "ci" =>             // line 230
(isset($context["ci"]) ? $context["ci"] : null), "ci_offset" =>             // line 231
(isset($context["ci_offset"]) ? $context["ci_offset"] : null), "column_meta" =>             // line 232
(isset($context["column_meta"]) ? $context["column_meta"] : null), "mime_map" =>             // line 233
(isset($context["mime_map"]) ? $context["mime_map"] : null), "type_prefix" => "input_"));
            // line 236
            echo "        ";
            $context["ci"] = ((isset($context["ci"]) ? $context["ci"] : null) + 1);
            // line 237
            echo "    </td>
";
        }
    }

    public function getTemplateName()
    {
        return "columns_definitions/column_attributes.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  365 => 237,  362 => 236,  360 => 233,  359 => 232,  358 => 231,  357 => 230,  356 => 229,  354 => 228,  350 => 225,  347 => 224,  345 => 221,  344 => 220,  343 => 219,  342 => 218,  341 => 217,  340 => 216,  338 => 215,  334 => 212,  331 => 211,  329 => 208,  328 => 207,  327 => 206,  326 => 205,  325 => 204,  323 => 203,  319 => 200,  316 => 199,  314 => 196,  313 => 195,  312 => 194,  311 => 193,  310 => 192,  309 => 191,  307 => 190,  303 => 187,  300 => 186,  298 => 184,  297 => 183,  296 => 182,  295 => 181,  294 => 180,  293 => 179,  291 => 178,  288 => 176,  286 => 175,  283 => 174,  279 => 172,  276 => 171,  274 => 169,  273 => 168,  272 => 167,  271 => 166,  270 => 165,  269 => 164,  268 => 163,  264 => 161,  257 => 160,  254 => 159,  251 => 158,  245 => 157,  242 => 156,  239 => 155,  236 => 154,  234 => 153,  230 => 150,  227 => 149,  225 => 147,  224 => 146,  223 => 145,  222 => 144,  221 => 143,  220 => 142,  219 => 141,  218 => 140,  215 => 139,  213 => 138,  210 => 136,  207 => 135,  205 => 133,  204 => 132,  203 => 131,  202 => 130,  201 => 129,  200 => 128,  199 => 127,  198 => 126,  196 => 125,  192 => 122,  189 => 121,  187 => 119,  186 => 118,  185 => 117,  184 => 116,  182 => 115,  179 => 113,  175 => 111,  172 => 110,  170 => 108,  169 => 107,  168 => 106,  167 => 105,  166 => 104,  163 => 103,  161 => 102,  159 => 101,  155 => 99,  152 => 98,  150 => 96,  149 => 95,  148 => 94,  147 => 93,  146 => 92,  143 => 91,  141 => 90,  139 => 89,  136 => 88,  133 => 87,  131 => 85,  130 => 84,  129 => 83,  128 => 82,  126 => 81,  122 => 78,  119 => 77,  117 => 75,  116 => 74,  115 => 73,  114 => 72,  113 => 71,  112 => 70,  111 => 69,  109 => 68,  105 => 65,  103 => 64,  100 => 63,  98 => 61,  97 => 60,  96 => 59,  95 => 58,  94 => 57,  92 => 56,  88 => 53,  85 => 52,  83 => 50,  82 => 49,  81 => 48,  80 => 47,  79 => 46,  78 => 45,  76 => 44,  72 => 41,  69 => 40,  67 => 38,  66 => 37,  65 => 36,  64 => 35,  63 => 34,  61 => 33,  57 => 30,  54 => 29,  52 => 27,  51 => 26,  50 => 25,  49 => 24,  48 => 23,  46 => 22,  42 => 19,  39 => 18,  37 => 16,  36 => 15,  35 => 14,  34 => 13,  33 => 12,  32 => 11,  30 => 10,  26 => 7,  24 => 6,  21 => 3,  19 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "columns_definitions/column_attributes.twig", "/home/wwwroot/default/phpmyadmin/templates/columns_definitions/column_attributes.twig");
    }
}
