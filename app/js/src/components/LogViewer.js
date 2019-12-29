/**
 * Main.
 *
 * @param {Object} props
 */
const LogViewer = ( props ) => {
	return (
		<textarea
			id="wp-live-debug-area"
			name="wp-live-debug-area"
			spellCheck="false"
			value={ props.deubgLogContent }
		/>
	);
};

export default LogViewer;
