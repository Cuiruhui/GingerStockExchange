<?php

/* display/export/options_quick_export.twig */
class __TwigTemplate_2364db5330e8b3bc3d2f28cbbb371854bd0228e19c7c02c050f0d41d70a7ad7a extends Twig_Template
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
        echo "<div class=\"exportoptions\" id=\"output_quick_export\">
    <h3>";
        // line 2
        echo _gettext("Output:");
        echo "</h3>
    <ul>
        <li>
            <input type=\"checkbox\" name=\"quick_export_onserver\" value=\"saveit\"
                id=\"checkbox_quick_dump_onserver\"";
        // line 6
        echo (((isset($context["export_is_checked"]) ? $context["export_is_checked"] : null)) ? (" checked") : (""));
        echo ">
            <label for=\"checkbox_quick_dump_onserver\">
                ";
        // line 8
        echo sprintf(_gettext("Save on server in the directory <strong>%s</strong>"), twig_escape_filter($this->env, (isset($context["save_dir"]) ? $context["save_dir"] : null)));
        echo "
            </label>
        </li>
        <li>
            <input type=\"checkbox\" name=\"quick_export_onserver_overwrite\"
                value=\"saveitover\" id=\"checkbox_quick_dump_onserver_overwrite\"";
        // line 14
        echo (((isset($context["export_overwrite_is_checked"]) ? $context["export_overwrite_is_checked"] : null)) ? (" checked") : (""));
        echo ">
            <label for=\"checkbox_quick_dump_onserver_overwrite\">
                ";
        // line 16
        echo _gettext("Overwrite existing file(s)");
        // line 17
        echo "            </label>
        </li>
    </ul>
</div>
";
    }

    public function getTemplateName()
    {
        return "display/export/options_quick_export.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 17,  47 => 16,  42 => 14,  34 => 8,  29 => 6,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "display/export/options_quick_export.twig", "/home/wwwroot/default/phpmyadmin/templates/display/export/options_quick_export.twig");
    }
}
