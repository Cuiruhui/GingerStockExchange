<?php

/* columns_definitions/table_fields_definitions.twig */
class __TwigTemplate_aa191e40ed616677063d0aed8bc6864e562aa7c867248141f2612d8fde10a983 extends Twig_Template
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
        // line 1
        echo "<div class=\"responsivetable\">
<table id=\"table_columns\" class=\"noclick\">
    <caption class=\"tblHeaders\">
        ";
        // line 4
        echo _gettext("Structure");
        // line 5
        echo "        ";
        echo PhpMyAdmin\Util::showMySQLDocu("CREATE_TABLE");
        echo "
    </caption>
    <tr>
        <th>
            ";
        // line 9
        echo _gettext("Name");
        // line 10
        echo "        </th>
        <th>
            ";
        // line 12
        echo _gettext("Type");
        // line 13
        echo "            ";
        echo PhpMyAdmin\Util::showMySQLDocu("data-types");
        echo "
        </th>
        <th>
            ";
        // line 16
        echo _gettext("Length/Values");
        // line 17
        echo "            ";
        echo PhpMyAdmin\Util::showHint(_gettext("If column type is \"enum\" or \"set\", please enter the values using this format: 'a','b','c'…<br />If you ever need to put a backslash (\"\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
        echo "
        </th>
        <th>
            ";
        // line 20
        echo _gettext("Default");
        // line 21
        echo "            ";
        echo PhpMyAdmin\Util::showHint(_gettext("For default values, please enter just a single value, without backslash escaping or quotes, using this format: a"));
        echo "
        </th>
        <th>
            ";
        // line 24
        echo _gettext("Collation");
        // line 25
        echo "        </th>
        <th>
            ";
        // line 27
        echo _gettext("Attributes");
        // line 28
        echo "        </th>
        <th>
            ";
        // line 30
        echo _gettext("Null");
        // line 31
        echo "        </th>

        ";
        // line 34
        echo "        ";
        if ((array_key_exists("change_column", $context) &&  !twig_test_empty((isset($context["change_column"]) ? $context["change_column"] : null)))) {
            // line 35
            echo "            <th>
                ";
            // line 36
            echo _gettext("Adjust privileges");
            // line 37
            echo "                ";
            echo PhpMyAdmin\Util::showDocu("faq", "faq6-39");
            echo "
            </th>
        ";
        }
        // line 40
        echo "
        ";
        // line 44
        echo "        ";
        if ( !(isset($context["is_backup"]) ? $context["is_backup"] : null)) {
            // line 45
            echo "            <th>
                ";
            // line 46
            echo _gettext("Index");
            // line 47
            echo "            </th>
        ";
        }
        // line 49
        echo "
        <th>
            <abbr title=\"AUTO_INCREMENT\">A_I</abbr>
        </th>
        <th>
            ";
        // line 54
        echo _gettext("Comments");
        // line 55
        echo "        </th>

        ";
        // line 57
        if ((isset($context["is_virtual_columns_supported"]) ? $context["is_virtual_columns_supported"] : null)) {
            // line 58
            echo "            <th>
                ";
            // line 59
            echo _gettext("Virtuality");
            // line 60
            echo "            </th>
        ";
        }
        // line 62
        echo "
        ";
        // line 63
        if (array_key_exists("fields_meta", $context)) {
            // line 64
            echo "            <th>
                ";
            // line 65
            echo _gettext("Move column");
            // line 66
            echo "            </th>
        ";
        }
        // line 68
        echo "
        ";
        // line 69
        if (((isset($context["mimework"]) ? $context["mimework"] : null) && (isset($context["browse_mime"]) ? $context["browse_mime"] : null))) {
            // line 70
            echo "            <th>
                ";
            // line 71
            echo _gettext("MIME type");
            // line 72
            echo "            </th>
            <th>
                <a href=\"transformation_overview.php";
            // line 75
            echo PhpMyAdmin\Url::getCommon();
            echo "#transformation\" title=\"";
            // line 76
            echo _gettext("List of available transformations and their options");
            // line 77
            echo "\" target=\"_blank\">
                    ";
            // line 78
            echo _gettext("Browser display transformation");
            // line 79
            echo "                </a>
            </th>
            <th>
                ";
            // line 82
            echo _gettext("Browser display transformation options");
            // line 83
            echo "                ";
            echo PhpMyAdmin\Util::showHint(_gettext("Please enter the values for transformation options using this format: 'a', 100, b,'c'…<br />If you ever need to put a backslash (\"\\\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
            echo "
            </th>
            <th>
                <a href=\"transformation_overview.php<?= PhpMyAdmin\\Url::getCommon(); ?>#input_transformation\"
                   title=\"";
            // line 87
            echo _gettext("List of available transformations and their options");
            echo "\"
                   target=\"_blank\">
                    ";
            // line 89
            echo _gettext("Input transformation");
            // line 90
            echo "                </a>
            </th>
            <th>
                ";
            // line 93
            echo _gettext("Input transformation options");
            // line 94
            echo "                ";
            echo PhpMyAdmin\Util::showHint(_gettext("Please enter the values for transformation options using this format: 'a', 100, b,'c'…<br />If you ever need to put a backslash (\"\\\") or a single quote (\"'\") amongst those values, precede it with a backslash (for example '\\\\xyz' or 'a\\'b')."));
            echo "
            </th>
        ";
        }
        // line 97
        echo "    </tr>
    ";
        // line 98
        $context["options"] = array("" => "", "VIRTUAL" => "VIRTUAL");
        // line 99
        echo "    ";
        if (((isset($context["server_type"]) ? $context["server_type"] : null) == "MariaDB")) {
            // line 100
            echo "        ";
            $context["options"] = twig_array_merge((isset($context["options"]) ? $context["options"] : null), array("PERSISTENT" => "PERSISTENT"));
            // line 101
            echo "    ";
        } else {
            // line 102
            echo "        ";
            $context["options"] = twig_array_merge((isset($context["options"]) ? $context["options"] : null), array("STORED" => "STORED"));
            // line 103
            echo "    ";
        }
        // line 104
        echo "    ";
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["content_cells"]) ? $context["content_cells"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["content_row"]) {
            // line 105
            echo "        <tr>
            ";
            // line 106
            $this->loadTemplate("columns_definitions/column_attributes.twig", "columns_definitions/table_fields_definitions.twig", 106)->display(twig_array_merge($context["content_row"], array("options" =>             // line 107
(isset($context["options"]) ? $context["options"] : null), "change_column" =>             // line 108
(isset($context["change_column"]) ? $context["change_column"] : null), "is_virtual_columns_supported" =>             // line 109
(isset($context["is_virtual_columns_supported"]) ? $context["is_virtual_columns_supported"] : null), "browse_mime" =>             // line 110
(isset($context["browse_mime"]) ? $context["browse_mime"] : null), "max_rows" =>             // line 111
(isset($context["max_rows"]) ? $context["max_rows"] : null), "char_editing" =>             // line 112
(isset($context["char_editing"]) ? $context["char_editing"] : null), "attribute_types" =>             // line 113
(isset($context["attribute_types"]) ? $context["attribute_types"] : null), "privs_available" =>             // line 114
(isset($context["privs_available"]) ? $context["privs_available"] : null), "max_length" =>             // line 115
(isset($context["max_length"]) ? $context["max_length"] : null), "dbi" =>             // line 116
(isset($context["dbi"]) ? $context["dbi"] : null), "disable_is" =>             // line 117
(isset($context["disable_is"]) ? $context["disable_is"] : null))));
            // line 119
            echo "        </tr>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['content_row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 121
        echo "</table>
</div>
";
    }

    public function getTemplateName()
    {
        return "columns_definitions/table_fields_definitions.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  261 => 121,  254 => 119,  252 => 117,  251 => 116,  250 => 115,  249 => 114,  248 => 113,  247 => 112,  246 => 111,  245 => 110,  244 => 109,  243 => 108,  242 => 107,  241 => 106,  238 => 105,  233 => 104,  230 => 103,  227 => 102,  224 => 101,  221 => 100,  218 => 99,  216 => 98,  213 => 97,  206 => 94,  204 => 93,  199 => 90,  197 => 89,  192 => 87,  184 => 83,  182 => 82,  177 => 79,  175 => 78,  172 => 77,  170 => 76,  167 => 75,  163 => 72,  161 => 71,  158 => 70,  156 => 69,  153 => 68,  149 => 66,  147 => 65,  144 => 64,  142 => 63,  139 => 62,  135 => 60,  133 => 59,  130 => 58,  128 => 57,  124 => 55,  122 => 54,  115 => 49,  111 => 47,  109 => 46,  106 => 45,  103 => 44,  100 => 40,  93 => 37,  91 => 36,  88 => 35,  85 => 34,  81 => 31,  79 => 30,  75 => 28,  73 => 27,  69 => 25,  67 => 24,  60 => 21,  58 => 20,  51 => 17,  49 => 16,  42 => 13,  40 => 12,  36 => 10,  34 => 9,  26 => 5,  24 => 4,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "columns_definitions/table_fields_definitions.twig", "/home/wwwroot/default/phpmyadmin/templates/columns_definitions/table_fields_definitions.twig");
    }
}
