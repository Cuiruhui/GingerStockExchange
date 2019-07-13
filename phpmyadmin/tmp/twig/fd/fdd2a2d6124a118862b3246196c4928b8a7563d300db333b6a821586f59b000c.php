<?php

/* display/export/options_output_separate_files.twig */
class __TwigTemplate_79709c4d5d7a1e5bb48511a79f99af147346d9a941ea03d6bff73213f48fcd20 extends Twig_Template
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
        echo "<li>
    <input type=\"checkbox\" id=\"checkbox_as_separate_files\"
        name=\"as_separate_files\" value=\"";
        // line 3
        echo twig_escape_filter($this->env, (isset($context["export_type"]) ? $context["export_type"] : null), "html", null, true);
        echo "\"";
        // line 4
        echo (((isset($context["is_checked"]) ? $context["is_checked"] : null)) ? (" checked") : (""));
        echo ">
    <label for=\"checkbox_as_separate_files\">
        ";
        // line 6
        if (((isset($context["export_type"]) ? $context["export_type"] : null) == "server")) {
            // line 7
            echo "            ";
            echo _gettext("Export databases as separate files");
            // line 8
            echo "        ";
        } elseif (((isset($context["export_type"]) ? $context["export_type"] : null) == "database")) {
            // line 9
            echo "            ";
            echo _gettext("Export tables as separate files");
            // line 10
            echo "        ";
        }
        // line 11
        echo "    </label>
</li>
";
    }

    public function getTemplateName()
    {
        return "display/export/options_output_separate_files.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  45 => 11,  42 => 10,  39 => 9,  36 => 8,  33 => 7,  31 => 6,  26 => 4,  23 => 3,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/export/options_output_separate_files.twig", "/home/wwwroot/default/phpmyadmin/templates/display/export/options_output_separate_files.twig");
    }
}
