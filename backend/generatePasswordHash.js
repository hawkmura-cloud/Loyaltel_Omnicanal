const bcrypt = require('bcryptjs');

async function generateHash() {
  const password = 'superadmin123';
  const hash = await bcrypt.hash(password, 10);
  console.log('Password hash for superadmin123:');
  console.log(hash);
}

generateHash();
