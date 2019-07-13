<?php

/* table/structure/action_row_in_structure_table.twig */
class __TwigTemplate_f1f91564156da8c85f7bc1fdb6e2f9e982fef44ef4e2b0d805c582ef34ef7af7 extends Twig_Template
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
        echo "<li class=\"";
        echo twig_escape_filter($this->env, (isset($context["class"]) ? $context["class"] : null), "html", null, true);
        echo "\">
";
        // line 2
        if ((((((isset($context["type"]) ? $context["type"] : null) == "text") || (        // line 3
(isset($context["type"]) ? $context["type"] : null) == "blob")) || (        // line 4
(isset($context["tbl_storage_engine"]) ? $context["tbl_storage_engine"] : null) == "ARCHIVE")) ||         // line 5
(isset($context["has_field"]) ? $context["has_field"] : null))) {
            // line 6
            echo "    ";
            echo $this->getAttribute((isset($context["titles"]) ? $context["titles"] : null), ("No" . (isset($context["action"]) ? $context["action"] : null)), array(), "array");
            echo "
";
        } else {
            // line 8
            echo "    <a rel=\"samepage\" class=\"ajax add_key print_ignore";
            // line 9
            if ((isset($context["has_link_class"]) ? $context["has_link_class"] : null)) {
                // line 10
                echo "            add_primary_key_anchor";
            } elseif ((            // line 11
(isset($context["action"]) ? $context["action"] : null) == "Index")) {
                // line 12
                echo "            add_index_anchor";
            } elseif ((            // line 13
(isset($context["action"]) ? $context["action"] : null) == "Unique")) {
                // line 14
                echo "            add_unique_anchor";
            } elseif ((            // line 15
(isset($context["action"]) ? $context["action"] : null) == "Spatial")) {
                // line 16
                echo "            add_spatial_anchor";
            }
            // line 17
            echo "\" href=\"tbl_structure.php\" data-post=\"";
            echo (isset($context["url_query"]) ? $context["url_query"] : null);
            // line 18
            echo "&amp;add_key=1&amp;sql_query=";
            // line 19
            echo twig_escape_filter($this->env, twig_urlencode_filter(((((((("ALTER TABLE " . PhpMyAdmin\Util::backquote(            // line 20
(isset($context["table"]) ? $context["table"] : null))) . ((            // line 21
(isset($context["is_primary"]) ? $context["is_primary"] : null)) ? ((((isset($context["primary"]) ? $context["primary"] : null)) ? (" DROP PRIMARY KEY,") : (""))) : (""))) . " ") .             // line 23
(isset($context["syntax"]) ? $context["syntax"] : null)) . "(") . PhpMyAdmin\Util::backquote($this->getAttribute(            // line 25
(isset($context["row"]) ? $context["row"] : null), "Field", array(), "array"))) . ");")), "html", null, true);
            // line 27
            echo "&amp;message_to_show=";
            echo twig_escape_filter($this->env, twig_urlencode_filter(sprintf((isset($context["message"]) ? $context["message"] : null), twig_escape_filter($this->env, $this->getAttribute((isset($context["row"]) ? $context["row"] : null), "Field", array(), "array")))), "html", null, true);
            echo "\">
        ";
            // line 28
            echo $this->getAttribute((isset($context["titles"]) ? $context["titles"] : null), (isset($context["action"]) ? $context["action"] : null), array(), "array");
            echo "
    </a>
";
        }
        // line 31
        echo "</li>
";
    }

    public function getTemplateName()
    {
        return "table/structure/action_row_in_structure_table.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  76 => 31,  70 => 28,  65 => 27,  63 => 25,  62 => 23,  61 => 21,  60 => 20,  59 => 19,  57 => 18,  54 => 17,  51 => 16,  49 => 15,  47 => 14,  45 => 13,  43 => 12,  41 => 11,  39 => 10,  37 => 9,  35 => 8,  29 => 6,  27 => 5,  26 => 4,  25 => 3,  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "table/structure/action_row_in_structure_table.twig", "/home/wwwroot/default/phpmyadmin/templates/table/structure/action_row_in_structure_table.twig");
    }
}
