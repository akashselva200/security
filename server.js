require('dotenv').config();
const express = require('express');
const cors = require('cors');
const nodemailer = require('nodemailer');

const app = express();
app.use(cors());
app.use(express.json());

const port = process.env.PORT || 5000;

app.listen(port, () => {
  console.log(`Server running on port ${port}`);
});

const transporter = nodemailer.createTransport({
  service: 'gmail', // or another email service
  auth: {
    user: process.env.EMAIL_USER, // Your email
    pass: process.env.EMAIL_PASS, // Your email password or app password
  },
});

transporter.verify((error) => {
  if (error) {
    console.log('Error with email transporter:', error);
  } else {
    console.log('Ready to Send Emails');
  }
});

app.post('/contact', (req, res) => {
  const { name, email, subject, message } = req.body;

  if (!name || !email || !subject || !message) {
    return res.status(400).json({ status: 'fail', message: 'All fields are required.' });
  }

  const mail = {
    from: name,
    to: process.env.EMAIL_USER, // The email you want to receive messages at
    subject: `Contact Form Submission: ${subject}`,
    html: `<p>Name: ${name}</p>
           <p>Email: ${email}</p>
           <p>Message: ${message}</p>`,
  };

  transporter.sendMail(mail, (error) => {
    if (error) {
      console.error('Error sending email:', error);
      res.json({ status: 'fail' });
    } else {
      res.json({ status: 'success' });
    }
  });
});
