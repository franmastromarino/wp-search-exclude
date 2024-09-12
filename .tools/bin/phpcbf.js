const { execSync } = require('child_process');

// Construct the command
const command = `php .tools/vendor/bin/phpcbf --standard=./phpcs.xml  --extensions=php ./`;

try {
	// Run the command
	execSync(command, { stdio: 'inherit' });
	console.log(`phpcbf format has been executed`);
} catch (error) {
	if (error.status === 1) {
		console.log('PHP CodeSniffer found coding standard violations.');
	} else {
		console.error(
			'An error occurred while executing phpcbf format.',
			error.status
		);
	}
}
