/**
 * WordPress dependencies.
 */
import { Component } from '@wordpress/element';

/**
 * Main.
 */
class LogViewer extends Component {
	render() {
		return (
			<textarea
				id="wp-live-debug-area"
				name="wp-live-debug-area"
				spellCheck="false"
			>
				aefaeafaef aefae aef ae
			</textarea>
		);
	}
}

export default LogViewer;
