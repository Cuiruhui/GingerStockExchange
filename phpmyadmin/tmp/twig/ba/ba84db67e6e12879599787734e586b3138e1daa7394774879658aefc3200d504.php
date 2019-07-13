<?php

/* display/results/additional_fields.twig */
class __TwigTemplate_0c59a0aa42706cacedefba80d1a1b58188e309eea01292023532db36961dfe08 extends Twig_Template
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
        echo "<input type=\"hidden\" name=\"sql_query\" value=\"";
        echo (isset($context["sql_query"]) ? $context["sql_query"] : null);
        echo "\" />
<input type=\"hidden\" name=\"goto\" value=\"";
        // line 2
        echo twig_escape_filter($this->env, (isset($context["goto"]) ? $context["goto"] : null), "html", null, true);
        echo "\" />
";
        // line 4
        echo "<input type=\"hidden\" name=\"pos\" size=\"3\" value=\"";
        echo twig_escape_filter($this->env, (isset($context["pos"]) ? $context["pos"] : null), "html", null, true);
        echo "\" />
<input type=\"hidden\" name=\"is_browse_distinct\" value=\"";
        // line 5
        echo twig_escape_filter($this->env, (isset($context["is_browse_distinct"]) ? $context["is_browse_distinct"] : null), "html", null, true);
        echo "\" />
";
        // line 6
        echo _gettext("Number of rows:");
        // line 7
        echo PhpMyAdmin\Util::getDropdown("session_max_rows",         // line 9
(isset($context["number_of_rows_choices"]) ? $context["number_of_rows_choices"] : null),         // line 10
(isset($context["max_rows"]) ? $context["max_rows"] : null), "", "autosubmit",         // line 13
(isset($context["number_of_rows_placeholder"]) ? $context["number_of_rows_placeholder"] : null));
        // line 14
        echo "
";
    }

    public function getTemplateName()
    {
        return "display/results/additional_fields.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 14,  42 => 13,  41 => 10,  40 => 9,  39 => 7,  37 => 6,  33 => 5,  28 => 4,  24 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/results/additional_fields.twig", "/home/wwwroot/default/phpmyadmin/templates/display/results/additional_fields.twig");
    }
}
