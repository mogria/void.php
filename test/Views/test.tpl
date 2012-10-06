<h1>This is just a Test Template</h1>
{for($x = 0; $x < 2; $x++):}
<p>This is just some random Text to Test the Template Engine. {=date('d.m.Y', 0)}</p>
{endfor}
{[$included}
<p>encoded: {>"<>\""}</p>