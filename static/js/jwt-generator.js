let data = base64urlEncode(header) + "." + base64urlEncode(payload);
let hashedData = hash(data, secret);
let signature = base64urlEncode(hashedData);