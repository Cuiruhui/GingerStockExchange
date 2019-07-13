<?php

/* dropdown.twig */
class __TwigTemplate_4fca8738a698b042e914e56549efe7f851c3b8fd9746777214fbfd94658abde5 extends Twig_Template
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
        echo "<select name=\"";
        echo twig_escape_filter($this->env, (isset($context["select_name"]) ? $context["select_name"] : null), "html", null, true);
        echo "\"";
        if ( !twig_test_empty((isset($context["id"]) ? $context["id"] : null))) {
            echo " id=\"";
            echo twig_escape_filter($this->env, (isset($context["id"]) ? $context["id"] : null), "html", null, true);
            echo "\"";
        }
        // line 2
        if ( !twig_test_empty((isset($context["class"]) ? $context["class"] : null))) {
            echo " class=\"";
            echo twig_escape_filter($this->env, (isset($context["class"]) ? $context["class"] : null), "html", null, true);
            echo "\"";
        }
        echo ">
";
        // line 3
        if ( !twig_test_empty((isset($context["placeholder"]) ? $context["placeholder"] : null))) {
            // line 4
            echo "    <option value=\"\" disabled=\"disabled\"";
            // line 5
            if ( !(isset($context["selected"]) ? $context["selected"] : null)) {
                echo " selected=\"selected\"";
            }
            echo ">";
            echo twig_escape_filter($this->env, (isset($context["placeholder"]) ? $context["placeholder"] : null), "html", null, true);
            echo "</option>
";
        }
        // line 7
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["result_options"]) ? $context["result_options"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
            // line 8
            echo "<option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "value", array(), "array"), "html", null, true);
            echo "\"";
            // line 9
            echo (($this->getAttribute($context["option"], "selected", array(), "array")) ? (" selected=\"selected\"") : (""));
            echo ">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["option"], "label", array(), "array"), "html", null, true);
            echo "</option>
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 11
        echo "</select>
";
    }

    public function getTemplateName()
    {
        return "dropdown.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  67 => 11,  57 => 9,  53 => 8,  49 => 7,  40 => 5,  38 => 4,  36 => 3,  28 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "dropdown.twig", "/home/wwwroot/default/phpmyadmin/templates/dropdown.twig");
    }
}
