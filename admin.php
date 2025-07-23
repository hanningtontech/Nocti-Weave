<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dreams & Culture Questionnaire</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      background: #f8f9fa;
    }
    .container {
      max-width: 600px;
      margin: 40px auto;
      background: #fff;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.07);
      padding: 24px 20px;
    }
    h1, h2 {
      text-align: center;
      margin-top: 0;
    }
    label {
      display: block;
      margin: 10px 0 5px;
      font-weight: 500;
    }
    input, select, textarea {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 16px; /* Prevent mobile zoom */
      box-sizing: border-box;
      background: #f9f9f9;
      transition: border 0.2s;
    }
    input:focus, select:focus, textarea:focus {
      border: 1.5px solid #007bff;
      outline: none;
      background: #fff;
    }
    textarea {
      min-height: 80px;
      resize: vertical;
      font-size: 16px;
      line-height: 1.5;
    }
    button {
      padding: 12px 24px;
      background: #007bff;
      color: #fff;
      border: none;
      border-radius: 5px;
      font-size: 18px;
      cursor: pointer;
      width: 100%;
      margin-top: 10px;
      transition: background 0.2s;
    }
    button:hover {
      background: #0056b3;
    }
    @media (max-width: 700px) {
      .container {
        max-width: 98vw;
        margin: 10px 1vw;
        padding: 16px 6px;
      }
      h1, h2 {
        font-size: 1.3em;
      }
      input, select, textarea {
        font-size: 16px;
      }
    }
    @media (max-width: 400px) {
      .container {
        padding: 6px 2px;
      }
      h1, h2 {
        font-size: 1em;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Dreams & Culture Questionnaire</h1>
    <form action="submit.php" method="POST">
      <h2>Section 1: About You</h2>
      <label>What is your age?</label>
      <input type="number" name="age" required>

      <label>What is your gender?</label>
      <input type="text" name="gender" required>

      <label>What is your tribe or cultural background?</label>
      <input type="text" name="tribe" required>

      <label>What language(s) do you speak at home?</label>
      <input type="text" name="languages" required>

      <label>Where do you currently live (village, town, county, country)?</label>
      <input type="text" name="location" required>

      <h2>Section 2: Dream Habits</h2>
      <label>How often do you remember your dreams?</label>
      <select name="dream_frequency" required>
        <option>Every night</option>
        <option>A few times a week</option>
        <option>Occasionally</option>
        <option>Rarely</option>
        <option>Never</option>
      </select>

      <label>When you remember a dream, do you usually tell someone about it?</label>
      <select name="tell_someone" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <label>If yes, who do you tell? (Family, friends, spiritual leader, etc.)</label>
      <input type="text" name="tell_whom">

      <label>Do you write down or record your dreams?</label>
      <select name="record_dreams" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <h2>Section 3: Cultural Beliefs About Dreams</h2>
      <label>In your culture or family, do people believe dreams have special meanings?</label>
      <select name="special_meanings" required>
        <option>Yes</option>
        <option>No</option>
        <option>Not Sure</option>
      </select>

      <label>Please explain your answer.</label>
      <textarea name="explanation"></textarea>

      <label>Are there any traditional stories, proverbs, or sayings about dreams in your community? Please share an example if you know one.</label>
      <textarea name="stories"></textarea>

      <label>Who do you go to for dream interpretation or advice?</label>
      <input type="text" name="go_to">

      <h2>Section 4: Personal Dream Experiences</h2>
      <label>Can you describe a dream you remember clearly?</label>
      <textarea name="dream_description"></textarea>

      <label>How did you feel during and after this dream?</label>
      <input type="text" name="dream_feeling">

      <label>Did you or others attach any meaning to this dream?</label>
      <select name="attach_meaning" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <label>If yes, what did it mean?</label>
      <textarea name="meaning_description"></textarea>

      <h2>Section 5: Dream Symbols and Meaning</h2>
      <label>Are there specific symbols, animals, or people that often appear in your dreams?</label>
      <textarea name="symbols"></textarea>

      <label>What do you think these symbols mean to you or your culture?</label>
      <textarea name="symbol_meanings"></textarea>

      <label>Are there dreams that are considered good or bad omens in your community? Please give examples.</label>
      <textarea name="omens_examples"></textarea>

      <h2>Section 6: Impact of Dreams</h2>
      <label>Has a dream ever influenced your real-life decisions or actions?</label>
      <select name="influenced" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <label>If yes, please describe.</label>
      <textarea name="influence_description"></textarea>

      <label>Do you ever pray, perform rituals, or seek help after certain dreams?</label>
      <select name="rituals" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <label>If yes, what kind of actions do you take?</label>
      <textarea name="ritual_actions"></textarea>

      <h2>Section 7: Openness to Sharing</h2>
      <label>Are you comfortable sharing your dreams with an AI for research and cultural understanding?</label>
      <select name="share_with_ai" required>
        <option>Yes</option>
        <option>No</option>
        <option>Maybe</option>
      </select>

      <label>Is there anything else about dreams and your culture you'd like to share?</label>
      <textarea name="additional_comments"></textarea>

      <h2>Optional Demographic Consent</h2>
      <label>Do you give permission for your (anonymous) responses to be used to help improve Nocti Weave Core’s cultural knowledge?</label>
      <select name="consent" required>
        <option>Yes</option>
        <option>No</option>
      </select>

      <button type="submit">Submit</button>
    </form>
  </div>
</body>
</html>
