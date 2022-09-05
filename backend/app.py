from flask import Flask, url_for, request, render_template, redirect, session, escape

app = Flask(__name__)


@app.route("/")
def index():
    if session.get("username") is not None:
        print(session["username"])
        return render_template("hupemap.html", logged_in=True)
    else:
        SQL = "SELECT * from services"
        links = database.read_data(SQL, "Services")
        return render_template("hupemap.html", links=links)


if __name__ == "__main__":
    app.secret_key = "testst"
    app.run(port=5000, debug=True, host="0.0.0.0")
