<?php 

use ActivismeBE\FormHelper\Form;
use Illuminate\Database\Eloquent\Model;

/**
 * Class OptionsTest
 */
class OptionsTest extends TestCase
{
    /**
     * @test
     * @testdox It generates options
     */
    public function it_generates_options(): void
    {
        $viewData = [
            'options' => [
                'option_a' => 'Option A',
                'option_b' => 'Option B',
            ],
            'default' => ['option_a', 'option_b']
        ];

        // No selected option
        $html  = '<option value="option_a">Option A</option>';
        $html .= '<option value="option_b">Option B</option>';

        $this->assertBladeRender($html, '@options($options, "select")', $viewData);

        // Default selected option
        $html  = '<option value="option_a">Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';

        $this->assertBladeRender($html, '@options($options, "select", "option_b")', $viewData);

        // Multiple default selected options
        $html  = '<option value="option_a" selected>Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';

        $this->assertBladeRender($html, '@options($options, "select", $default)', $viewData);
    }

    /**
     * @test
     * @testdox It generates options with placeholder
     */
    public function it_generates_options_with_placeholder(): void
    {
        $viewData = ['options' => ['option_value' => 'Option Text']];

        $html  = '<option value="" selected disabled>Placeholder</option>';
        $html .= '<option value="option_value">Option Text</option>';

        $this->assertBladeRender($html, '@options($options, "select", null, "Placeholder")', $viewData);
    }

    /**
     * @test
     * @testdox It generates option when the model does not have the attribute
     */
    public function it_generates_option_when_the_model_does_not_have_the_attribute(): void
    {
        $model = $this->prophesize(Model::class);
        $model->getAttribute('select')->willReturn(null);

        $viewData = [
            'model'   => $model->reveal(),
            'default' => ['option_a', 'option_b'],
            'options' => [
                'option_a' => 'Option A',
                'option_b' => 'Option B',
            ],
        ];

        // No selected option
        $html  = '<option value="option_a">Option A</option>';
        $html .= '<option value="option_b">Option B</option>';

        $this->assertBladeRender($html, '@form($model) @options($options, "select")', $viewData);

        // Default selected option
        $html  = '<option value="option_a">Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';

        $this->assertBladeRender($html, '@form($model) @options($options, "select", "option_b")', $viewData);

        // Multiple default selected options
        $html  = '<option value="option_a" selected>Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';

        $this->assertBladeRender($html, '@form($model) @options($options, "select", $default)', $viewData);
    }

    /**
     * @test
     * @testdox It generate options when the model exists
     */
    public function it_generates_options_when_the_model_exists(): void
    {
        $model = $this->prophesize(Model::class);
        $model->getAttribute('select')->willReturn('option_b');
        $model->getAttribute('select_multiple')->willReturn(['option_b', 'option_a']);
        
        $viewData = [
            'model' => $model->reveal(),
            'default' => ['option_a', 'option_b'],
            'options' => [
                'option_a' => 'Option A',
                'option_b' => 'Option B',
            ],
        ];

        // Selected option
        $html  = '<option value="option_a">Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';
        $this->assertBladeRender($html, '@form($model) @options($options, "select")', $viewData);

        // Ignore the default value because the model has the selected option
        $this->assertBladeRender($html, '@form($model) @options($options, "select", "option_a")', $viewData);

        // Multiple selected options
        $html  = '<option value="option_a" selected>Option A</option>';
        $html .= '<option value="option_b" selected>Option B</option>';

        $this->assertBladeRender($html, '@form($model) @options($options, "select_multiple")', $viewData);
    }

    /**
     * @test
     * @testdox It generates options when old input and the model exists
     */
    public function it_generates_options_when_old_input_and_the_model_exists(): void
    {
        $model = $this->prophesize(Model::class);
        $model->getAttribute('select')->willReturn('model_option_value');

        $viewData = [
            'model'   => $model->reveal(),
            'options' => ['option_value' => 'Option Text'],
        ];

        $this->session(['_old_input' => ['select' => 'option_value']]);
        $html = '<option value="option_value" selected>Option Text</option>';

        $this->assertBladeRender($html, '@form($model) @options($options, "select")', $viewData);
        $this->assertBladeRender($html, '@form($model) @options($options, "select", "default_value")', $viewData);
    }

    /**
     * @test
     * @testdox It generates options when old input exists
     */
    public function it_generates_options_when_old_input_exists(): void
    {
        $viewData = ['options' => ['option_value' => 'Option Text']];
        $this->session(['_old_input' => ['select' => 'option_value']]);

        $html = '<option value="option_value" selected>Option Text</option>';

        $this->assertBladeRender($html, '@options($options, "select")', $viewData);
        $this->assertBladeRender($html, '@options($options, "select", "default_value")', $viewData);
    }
}
