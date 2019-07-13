<?php

/* div_for_slider_effect.twig */
class __TwigTemplate_d3b99735a7dcef1c3431565b4fe95d00e38391f70a9b56bddfbc8724fd181b72 extends Twig_Template
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
        if (((isset($context["initial_sliders_state"]) ? $context["initial_sliders_state"] : null) == "disabled")) {
            // line 2
            echo "    <div";
            if (array_key_exists("id", $context)) {
                echo " id=\"";
                echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
                echo "\"";
            }
            echo ">
";
        } else {
            // line 4
            echo "    ";
            // line 12
            echo "    <div";
            if (array_key_exists("id", $context)) {
                echo " id=\"";
                echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
                echo "\"";
            }
            // line 13
            echo " ";
            if (((isset($context["initial_sliders_state"]) ? $context["initial_sliders_state"] : null) == "closed")) {
                // line 14
                echo "style=\"display: none; overflow:auto;\"";
            }
            echo " class=\"pma_auto_slider\"";
            // line 15
            if (array_key_exists("message", $context)) {
                echo " title=\"";
                echo twig_escape_filter($this->env, (isset($context["message"]) ? $context["message"] : null), "html", null, true);
                echo "\"";
            }
            echo ">
";
        }
    }

    public function getTemplateName()
    {
        return "div_for_slider_effect.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  47 => 15,  43 => 14,  40 => 13,  33 => 12,  31 => 4,  21 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "div_for_slider_effect.twig", "/home/wwwroot/default/phpmyadmin/templates/div_for_slider_effect.twig");
    }
}
