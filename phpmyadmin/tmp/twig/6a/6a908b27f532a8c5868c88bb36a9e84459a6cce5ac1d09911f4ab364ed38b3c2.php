<?php

/* display/results/show_all_checkbox.twig */
class __TwigTemplate_b5de7b08b0a902d583e1f4118272ace376fbfb12e3dc72816665092922efdaa8 extends Twig_Template
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
        echo "<td>
    <form action=\"sql.php\" method=\"post\">
        ";
        // line 3
        echo PhpMyAdmin\Url::getHiddenInputs((isset($context["db"]) ? $context["db"] : null), (isset($context["table"]) ? $context["table"] : null));
        echo "
        <input type=\"hidden\" name=\"sql_query\" value=\"";
        // line 4
        echo (isset($context["html_sql_query"]) ? $context["html_sql_query"] : null);
        echo "\" />
        <input type=\"hidden\" name=\"pos\" value=\"0\" />
        <input type=\"hidden\" name=\"is_browse_distinct\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, (isset($context["is_browse_distinct"]) ? $context["is_browse_distinct"] : null), "html", null, true);
        echo "\" />
        <input type=\"hidden\" name=\"session_max_rows\" value=\"";
        // line 7
        echo twig_escape_filter($this->env, (( !(isset($context["showing_all"]) ? $context["showing_all"] : null)) ? ("all") : ((isset($context["max_rows"]) ? $context["max_rows"] : null))), "html", null, true);
        echo "\" />
        <input type=\"hidden\" name=\"goto\" value=\"";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["goto"]) ? $context["goto"] : null), "html", null, true);
        echo "\" />
        <input type=\"checkbox\" name=\"navig\" id=\"showAll_";
        // line 9
        echo twig_escape_filter($this->env, (isset($context["unique_id"]) ? $context["unique_id"] : null), "html", null, true);
        echo "\" class=\"showAllRows\"";
        // line 10
        echo (((isset($context["showing_all"]) ? $context["showing_all"] : null)) ? (" checked=\"checked\"") : (""));
        echo " value=\"all\" />
        <label for=\"showAll_";
        // line 11
        echo twig_escape_filter($this->env, (isset($context["unique_id"]) ? $context["unique_id"] : null), "html", null, true);
        echo "\">";
        echo _gettext("Show all");
        echo "</label>
    </form>
</td>
";
    }

    public function getTemplateName()
    {
        return "display/results/show_all_checkbox.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  51 => 11,  47 => 10,  44 => 9,  40 => 8,  36 => 7,  32 => 6,  27 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/results/show_all_checkbox.twig", "/home/wwwroot/default/phpmyadmin/templates/display/results/show_all_checkbox.twig");
    }
}
