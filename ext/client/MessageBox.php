<?php

	class MessageBox
	{
		public function __construct() {
			$this->id = 'box';
			$this->class = 'box';
		}

		public string $id;
		public string $class;

		public function html() {
			return <<<HTML
				<div id="{$this->id}" class="{$this->class}">
					<p class="message" hidden></p>
					<p class="alert" hidden></p>
					<p class="error" hidden></p>
				</div>
			HTML;
		}
		public function js() {
			return <<<JS
				const MessageBox = {
					NONE:		-1,
					MESSAGE:	 0,
					ALERT:		 1,
					ERROR:		 2,
				
					Boxes: null,
					Current: 0,
				
					load: (root = document) => {
						MessageBox.Current = MessageBox.NONE;
						MessageBox.Boxes = root.getElementById('{$this->id}').children;
					},
					set: (type, html) => {
						MessageBox.show(type);
						MessageBox.Boxes[MessageBox.Current].innerHTML = html;
					},
				
					hide: () => {
						if (MessageBox.Current < 0)
							return;

						MessageBox.Boxes[MessageBox.Current].hidden = true;
						MessageBox.Current = MessageBox.NONE;
					},
					show: (type) => {
						if (type == MessageBox.Current)
							return;
							
						MessageBox.hide();
						MessageBox.Current = type;
						MessageBox.Boxes[MessageBox.Current].hidden = false;
					},
				};
			JS;
		}
	};

?>